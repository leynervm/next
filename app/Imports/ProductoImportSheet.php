<?php

namespace App\Imports;

use App\Models\Almacen;
use App\Models\Almacenarea;
use App\Models\Caracteristica;
use App\Models\Category;
use App\Models\Estante;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Subcategory;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeSheet;

class ProductoImportSheet implements ToModel, WithEvents, WithHeadingRow, WithValidation, WithUpserts, WithBatchInserts, WithChunkReading
{

    const HEADERS_IMPORT_PRODUCT = [
        /* 'item',  */
        'nombre',
        'categoria',
        'subcategoria',
        'marca',
        'codigo_unidad_medida',
        'unidad_medida',
        'area_almacen',
        'estante_almacen',
        'modelo',
        'sku',
        'numero_parte',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'publicado_web'
    ];

    private $empresa;
    private $headers_especificaciones = [];

    public function __construct()
    {
        $this->empresa = mi_empresa();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        DB::beginTransaction();
        try {
            if (!empty($row['nombre'])) {

                $category = Category::firstOrCreate([
                    'name' => toUTF8Import($row['categoria']),
                ], [
                    'orden' => 1 + Category::max('orden') ?? 0
                ]);
                $subcategory = Subcategory::firstOrCreate([
                    'name' => toUTF8Import($row['subcategoria']),
                ], [
                    'orden' => 1 + Subcategory::max('orden') ?? 0
                ]);
                $marca = Marca::firstOrCreate([
                    'name' => toUTF8Import($row['marca']),
                ]);
                $unit = Unit::firstOrCreate([
                    'code' => toUTF8Import($row['codigo_unidad_medida']),
                ], [
                    'name' => toUTF8Import($row['unidad_medida']),
                ]);

                if (!$category->subcategories()->where('subcategory_id', $subcategory->id)->exists()) {
                    $category->subcategories()->attach([$subcategory->id]);
                }

                $almacenarea = null;
                $estante = null;
                if (Module::isEnabled('Almacen')) {
                    if (!empty(toUTF8Import($row['area_almacen']))) {
                        $almacenarea = Almacenarea::firstOrCreate([
                            'name' => toUTF8Import($row['area_almacen']),
                        ]);
                    }
                    if (!empty(toUTF8Import($row['estante_almacen']))) {
                        $estante = Estante::firstOrCreate([
                            'name' => toUTF8Import($row['estante_almacen']),
                        ]);
                    }
                }

                $producto = Producto::updateOrCreate(
                    [
                        'name' => toUTF8Import($row['nombre']),
                    ],
                    [

                        'modelo' => toUTF8Import($row['modelo']),
                        'sku' => toUTF8Import($row['sku']),
                        'code' => Str::random(9),
                        'partnumber' => toUTF8Import($row['numero_parte']),
                        'pricebuy' => $row['precio_compra'],
                        'pricesale' => $row['precio_venta'],
                        'minstock' => !empty($row['stock_minimo']) ? $row['stock_minimo'] : 0,
                        'publicado' => in_array($row['publicado_web'], ['0', '1']) ? $row['publicado_web'] : 0,
                        'viewdetalle' => Module::isEnabled('Marketplace') ? Producto::VER_DETALLES : 0,
                        'viewespecificaciones' => Module::isEnabled('Marketplace') ? Producto::VER_DETALLES : 0,
                        'marca_id' => $marca->id,
                        'unit_id' => $unit->id,
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'almacenarea_id' => Module::isEnabled('Almacen') && !empty($almacenarea) ? $almacenarea->id : null,
                        'estante_id' => Module::isEnabled('Almacen') && !empty($estante) ? $estante->id : null,
                        'user_id'   => auth()->user()->id
                    ]
                );

                if (Module::isEnabled('Marketplace')) {
                    if (count($this->headers_especificaciones) > 0) {
                        foreach ($this->headers_especificaciones as $c) {
                            if (!empty(toUTF8Import($row[$c]))) {

                                $caracteristica = Caracteristica::firstOrCreate([
                                    'name' => str_replace("_", " ", toUTF8Import($c)),
                                ], [
                                    'orden' => 1 + Caracteristica::max('orden') ?? 0
                                ]);

                                $especificacion = $caracteristica->especificacions()
                                    ->firstOrCreate(['name' => toUTF8Import($row[$c])]);

                                $withPivotData = [$especificacion->id => [
                                    'orden' => 1 + $producto->especificacions()->max('orden') ?? 0,
                                ]];


                                if (!$producto->especificacions()->where('especificacion_id', $especificacion->id)->exists()) {
                                    //     $producto->especificacions()->updateExistingPivot($especificacion->id, [
                                    //         'orden' => 1 + $producto->especificacions()->max('orden') ?? 0,
                                    //     ]);
                                    // } else {
                                    $producto->especificacions()->syncWithoutDetaching($withPivotData);
                                }
                                // $producto->especificacions()->syncWithoutDetaching($withPivotData);
                            }
                        }
                    }
                }

                $producto->load(['promocions' => function ($query) {
                    $query->with(['itempromos.producto' => function ($subQuery) {
                        $subQuery->with('unit')->addSelect(['image' => function ($q) {
                            $q->select('url')->from('images')
                                ->whereColumn('images.imageable_id', 'productos.id')
                                ->where('images.imageable_type', Producto::class)
                                ->orderBy('default', 'desc')->limit(1);
                        }]);
                    }])->availables()->disponibles()->take(1);
                }]);
                $producto->assignPrice();
                $almacensDB = Almacen::get()->pluck('id')->toArray();
                $newalmacens = array_fill_keys($almacensDB, ['cantidad' => 0]);

                $almacens = $producto->almacens()->pluck('almacen_id')->toArray();
                $almacenSync = array_filter($newalmacens, function ($key) use ($almacens) {
                    return !in_array($key, $almacens);
                }, ARRAY_FILTER_USE_KEY);

                $producto->almacens()->syncWithoutDetaching($almacenSync);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3'],
            'modelo' => ['nullable', 'string', 'min:1'],
            'sku' => ['nullable', 'string', 'min:4'],
            'numero_parte' => ['nullable', 'string', 'min:4'],
            '*.precio_compra' => [
                'required',
                'numeric',
                'decimal:0,3',
                $this->empresa->usarLista() ? 'gt:0' : ''
            ],
            '*.precio_venta' => [
                'nullable',
                Rule::requiredIf(!$this->empresa->usarLista()),
                'numeric',
                'decimal:0,3',
                $this->empresa->usarLista() ? '' : 'gt:0'
            ],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'publicado_web' => ['nullable', 'integer', 'min:0', 'max:1'],
            'marca' => ['required', 'string', 'min:2'],
            'unidad_medida' => ['required', 'string', 'min:1'],
            'codigo_unidad_medida' => ['required', 'string', 'min:1'],
            'categoria' => ['required', 'string', 'min:2'],
            'subcategoria' => ['required', 'string', 'min:2',],
            'area_almacen' => ['nullable', 'string', 'min:1'],
            'estante_almacen' => ['nullable', 'string', 'min:1',],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'precio_compra.gt' => "El campo :attribute debe ser mayor que 0.",
            'precio_venta.gt' => "El campo :attribute debe ser mayor que 0.",
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'precio_compra' => 'precio compra',
            'precio_venta' => 'precio venta',
            'stock_minimo' => 'stock mínimo',
            'publicado_web' => 'publicado web',
            'unidad_medida' => 'unidad medida',
            'codigo_unidad_medida' => 'codigo unidad medida',
            'numero_parte' => 'numero parte',
            'area_almacen' => 'area almacen',
            'estante_almacen' => 'estante almacen'
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->beforeSheet($event);
            },
            // BeforeSheet::class => [self::class, 'beforeSheet'],
        ];
    }

    public function beforeSheet(BeforeSheet $event)
    {
        $sheet = $event->getSheet(0);
        $headers = self::extractHeaders($sheet, 'A', '1');
        self::validateHeaders($headers);
        $this->headers_especificaciones = self::extractHeadersEspecificaciones($headers);
    }

    private static function extractHeaders($sheet, $column, $cell,)
    {
        $headersArray = [];
        while ($sheet->getCell($column . $cell)->getValue() != '') {
            $headersArray[] = $sheet->getCell($column . $cell)->getValue();
            $column++;
        }

        return $headersArray;
    }

    private static function extractHeadersEspecificaciones(array $headers)
    {
        $headers_especificaciones = [];
        foreach ($headers as $header) {
            if (!in_array($header, self::HEADERS_IMPORT_PRODUCT)) {
                if ($header !== "item") {
                    $headers_especificaciones[] = $header;
                }
            }
        }

        return $headers_especificaciones;
    }

    private static function validateHeaders(array $headers)
    {

        if (count($headers) == 0) {
            throw new \Exception("El archivo importado no contiene los encabezados requeridos " .  implode(', ', self::HEADERS_IMPORT_PRODUCT));
        }

        foreach (self::HEADERS_IMPORT_PRODUCT as $header) {
            if (!in_array($header, $headers)) {
                throw new \Exception("El archivo importado no contiene el encabezado requerido: $header");
            }
        }
    }

    public function uniqueBy()
    {
        return ['name'];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
