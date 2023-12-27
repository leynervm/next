<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{

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
        return view('modules.almacen.productos.show', compact('producto'));
    }

}
