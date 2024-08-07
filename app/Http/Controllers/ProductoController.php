<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

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
        $producto = $producto->with(['series', 'images', 'marca', 'category', 'subcategory', 'unit', 'almacens', 'especificacions'])->find($producto->id);
        return view('modules.almacen.productos.show', compact('producto'));
    }
}
