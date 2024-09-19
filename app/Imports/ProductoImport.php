<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductoImport implements WithMultipleSheets
{

    use Importable;

    public function sheets(): array
    {
        return [
            'Productos' => new ProductoImportSheet(),
        ];
    }

    public function conditionalSheets(): array
    {
        return [
            'Productos' => new ProductoImportSheet(),
        ];
    }

    // public function collection(Collection $rows)
    // {

    //     Validator::make($rows->toArray(), [
    //         [
    //             '*.nombre' => ['required', 'string', 'min:3'],
    //             '*.modelo' => ['nullable', 'string', 'min:1'],
    //             '*.sku' => ['nullable', 'string', 'min:4'],
    //             '*.numero_parte' => ['nullable', 'string', 'min:4'],
    //             '*.precio_compra' => [
    //                 'required', 'numeric', 'decimal:0,4',
    //                 $this->empresa->usarLista() ? 'gt:0' : ''
    //             ],
    //             '*.precio_venta' => [
    //                 'nullable', Rule::requiredIf(!$this->empresa->usarLista()),
    //                 'numeric', 'decimal:0,4', $this->empresa->usarLista() ? '' : 'gt:0'
    //             ],
    //             '*.stock_minimo' => ['required', 'integer', 'min:0'],
    //             '*.publicado_web' => ['nullable', 'integer', 'min:0', 'max:1'],
    //             '*.marca' => ['required', 'string', 'min:2'],
    //             '*.unidad_medida' => ['required', 'string', 'min:1'],
    //             '*.codigo_unidad_medida' => ['required', 'string', 'min:1'],
    //             '*.categoria' => ['required', 'string', 'min:2'],
    //             '*.subcategoria' => ['required', 'string', 'min:2',],
    //             '*.area_almacen' => ['nullable', 'string', 'min:1'],
    //             '*.estante_almacen' => ['nullable', 'string', 'min:1',],
    //         ]
    //     ])->validate();

    //     foreach ($rows as $row) {
    //         try {
    //             DB::beginTransaction();

    //             if (empty(toUTF8Import($row['nombre']))) {
    //                 return null;
    //             }

    //             $category = Category::firstOrCreate([
    //                 'name' => toUTF8Import($row['categoria']),
    //             ]);
    //             $subcategory = Subcategory::firstOrCreate([
    //                 'name' => toUTF8Import($row['subcategoria']),
    //             ]);
    //             $marca = Marca::firstOrCreate([
    //                 'name' => toUTF8Import($row['marca']),
    //             ]);
    //             $unit = Unit::firstOrCreate([
    //                 'code' => toUTF8Import($row['codigo_unidad_medida']),
    //             ], [
    //                 'name' => toUTF8Import($row['unidad_medida']),
    //             ]);


    //             if (!$category->subcategories()->where('subcategory_id', $subcategory->id)->exists()) {
    //                 $category->attach([$subcategory->id]);
    //             }

    //             $almacenarea = null;
    //             $estante = null;
    //             if (Module::isEnabled('Almacen')) {
    //                 if (!empty(toUTF8Import($row['area_almacen']))) {
    //                     $almacenarea = Almacenarea::firstOrCreate([
    //                         'name' => toUTF8Import($row['area_almacen']),
    //                     ]);
    //                 }
    //                 if (!empty(toUTF8Import($row['estante_almacen']))) {
    //                     $estante = Estante::firstOrCreate([
    //                         'name' => toUTF8Import($row['estante_almacen']),
    //                     ]);
    //                 }
    //             }

    //             $producto = Producto::updateOrCreate(
    //                 [
    //                     'name' => toUTF8Import($row['nombre']),
    //                 ],
    //                 [
    //                     'modelo' => toUTF8Import($row['modelo']),
    //                     'sku' => toUTF8Import($row['sku']),
    //                     'code' => Str::random(9),
    //                     'partnumber' => toUTF8Import($row['numero_parte']),
    //                     'pricebuy' => $row['precio_compra'],
    //                     'pricesale' => $row['precio_venta'],
    //                     'minstock' => toUTF8Import($row['stock_minimo']),
    //                     'publicado' => toUTF8Import($row['publicado_web']),
    //                     'marca_id' => $marca->id,
    //                     'unit_id' => $unit->id,
    //                     'category_id' => $category->id,
    //                     'subcategory_id' => $subcategory->id,
    //                     'almacenarea_id' => Module::isEnabled('Almacen') && !empty($almacenarea) ? $almacenarea->id : null,
    //                     'estante_id' => Module::isEnabled('Almacen') && !empty($estante) ? $estante->id : null,
    //                 ]
    //             );
    //             DB::commit();
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             throw $e;
    //         } catch (\Throwable $e) {
    //             DB::rollBack();
    //             throw $e;
    //         }
    //     }
    // }

    // public function extractHeaders(array $array)
    // {
    //     $headersArray = [];
    //     $especificacionesHeader = [];
    //     $headers = array_keys($array) ?? [];
    //     foreach ($headers as $header) {
    //         if (in_array($header, $this->requiredHeaders)) {
    //             $headersArray[] = $header;
    //         } else {
    //             $especificacionesHeader[] = $header;
    //         }
    //     }

    //     // dd($headersArray, $especificacionesHeader);
    //     return [
    //         'headers_producto' => $headersArray,
    //         'headers_especificaciones' => $especificacionesHeader
    //     ];
    // }


}
