<?php

namespace App\Imports;

use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Rango;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeSheet;

class RangoImport implements ToModel, WithEvents, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, WithUpserts
{

    use Importable;

    const HEADERS_RANGOS = ['item', 'desde', 'hasta', 'incremento'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            $rango = Rango::updateOrCreate([
                'desde' => $row["desde"],
                'hasta' => $row["hasta"],
            ], [
                'incremento' => $row["incremento"],
            ]);

            $pricetypes = Pricetype::orderBy('id', 'asc')->pluck('id')->toArray();
            if (count($pricetypes) > 0) {
                foreach ($pricetypes as $p) {
                    if (!$rango->pricetypes()->where('pricetype_id', $p)->exists()) {
                        $withPivotData = [$p => [
                            'ganancia' => 0,
                        ]];

                        $rango->pricetypes()->syncWithoutDetaching($withPivotData);
                    }
                }

                $rango->load(['pricetypes']);
                $productos = Producto::query()->select('id', 'name', 'slug', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
                    ->with(['promocions' => function ($query) {
                        $query->with(['itempromos.producto' => function ($subQuery) {
                            $subQuery->with('unit')->addSelect(['image' => function ($q) {
                                $q->select('url')->from('images')
                                    ->whereColumn('images.imageable_id', 'productos.id')
                                    ->where('images.imageable_type', Producto::class)
                                    ->orderBy('default', 'desc')->limit(1);
                            }]);
                        }])->availables()->disponibles();
                    }])->whereRangoBetween($rango->desde, $rango->hasta)->get();


                if (count($productos) > 0 && count($rango->pricetypes) > 0) {
                    foreach ($productos as $producto) {
                        $firstPrm = count($producto->promocions) > 0 ? $producto->promocions->first() : null;
                        $promocion = verifyPromocion($firstPrm);

                        foreach ($rango->pricetypes as $lista) {
                            $precio_venta = getPriceDinamic(
                                $producto->pricebuy,
                                $lista->pivot->ganancia,
                                $rango->incremento,
                                $lista->rounded,
                                $lista->decimals,
                                $promocion
                            );

                            $producto->{$lista->campo_table} = $precio_venta;
                            $producto->save();
                        }
                    }
                }
            }
            // });

            // return new Rango([
            //     'desde' => $row["desde"],
            //     'hasta' => $row["hasta"],
            //     'incremento' => $row["incremento"],
            // ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            '*.desde' => ['required', 'numeric', 'decimal:0,2', 'gt:0'],
            '*.hasta' => ['required', 'numeric', 'decimal:0,2', 'gt:*.desde'],
            // '*.desde' => ['required', 'numeric', 'decimal:0,2', 'gt:*.0'],
            // '*.hasta' => ['required', 'numeric', 'decimal:0,2', 'gt:*.desde'],
            'incremento' => ['required', 'numeric', 'decimal:0,2', 'min:0'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'desde.gt' => "El campo :attribute debe ser mayor que 0.",
            // 'hasta.gt' => "El campo :attribute debe ser mayor que desde.",
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'desde' => 'rango inicio',
            'hasta' => 'rango final',
            'incremento' => 'porcentaje ganancia',
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->beforeSheet($event);
            },
        ];
    }

    public function beforeSheet(BeforeSheet $event)
    {
        $sheet = $event->getSheet(0);
        $headers = self::extractHeaders($sheet, 'A', '2');
        self::validateHeaders($headers);
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

    private static function validateHeaders(array $headers)
    {

        if (count($headers) == 0) {
            throw new \Exception("El archivo importado no contiene los encabezados requeridos " .  implode(', ', self::HEADERS_RANGOS));
        }

        foreach (self::HEADERS_RANGOS as $header) {
            if (!in_array($header, $headers)) {
                throw new \Exception("El archivo importado no contiene el encabezado requerido: $header");
            }
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy()
    {
        return ['desde', 'hasta'];
    }

    public function upsertColumns()
    {
        return ['incremento'];
    }
}
