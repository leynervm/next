<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Category;
use App\Models\Marca;
use App\Models\Methodpayment;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

class ApiController extends Controller
{
    public function productos(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'productos.novedad',
            'productos.pricebuy',
            'productos.pricesale',
            'precio_1',
            'precio_2',
            'precio_3',
            'precio_4',
            'precio_5',
            'requireserie',
            'marca_id',
            'category_id',
            'subcategory_id',
            'unit_id',
            'marcas.name as name_marca',
        )->addSelect(['image' => function ($query) {
            $query->select('url')->from('images')
                ->whereColumn('images.imageable_id', 'productos.id')
                ->where('images.imageable_type', Producto::class)
                ->orderBy('default', 'desc')->limit(1);
        }])->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'almacens']);

        // if (trim($search) !== '' && strlen(trim($search)) > 2) {
        //     $products->whereRaw(
        //         "to_tsvector('spanish', 
        //             COALESCE(productos.name, '') || ' ' || 
        //             COALESCE(marcas.name, '') || ' ' || 
        //             COALESCE(categories.name, '')) @@ plainto_tsquery('spanish', '" . $search . "')",
        //     )->orWhereRaw(
        //         "similarity(productos.name, '" . $search . "') > 0.5 
        //             OR similarity(marcas.name, '" . $search . "') > 0.5 
        //             OR similarity(categories.name, '" . $search . "') > 0.5",
        //     )->orderByDesc('novedad')->orderBy('subcategories.orden')
        //         ->orderBy('categories.orden')->orderByDesc('rank')
        //         ->orderByDesc(DB::raw("similarity(productos.name, '" . $search . "')"))
        //         ->visibles();
        // } else {
        $products->visibles()->orderByDesc('novedad')->orderBy('subcategories.orden')
            ->orderBy('categories.orden')->get()->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'name' => $producto->name,
                    'slug' => $producto->slug,
                    'requireserie' => $producto->isRequiredserie(),
                    'marca' => $producto->name_marca,
                    'unit' => $producto->unit->name,
                    'almacens' => $producto->almacens,
                    'image_url' => ($producto->image && pathURLProductImage($producto->image)) ? pathURLProductImage($producto->image) : null,
                ];
            });

        return response()->json($products);
    }
}
