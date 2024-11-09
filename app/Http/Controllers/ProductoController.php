<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.almacen.productos')->only('index');
        $this->middleware('can:admin.almacen.productos.create')->only('create');
        $this->middleware('can:admin.almacen.productos.edit')->only('edit');
    }

    public function index()
    {
        return view('modules.almacen.productos.index');
    }


    public function create()
    {
        return view('modules.almacen.productos.create');
    }


    public function edit(Producto $producto)
    {
        $producto->load([
            'almacens',
            'series.almacen',
            'detalleproducto',
            'marca',
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
            'subcategory',
            'especificacions.caracteristica',
            'detalleproducto',
            'garantiaproductos.typegarantia',
            'images' => function ($query) {
                $query->orderBy('default', 'desc');
            },
            'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($subQuery) {
                    $subQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles()->take(1);
            }
        ])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);

        // $producto = $producto->with(['series', 'images', 'marca', 'category', 'subcategory', 'unit', 'almacens', 'especificacions'])->find($producto->id);
        return view('modules.almacen.productos.show', compact('producto'));
    }
}
