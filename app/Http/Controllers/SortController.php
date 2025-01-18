<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Category;
use App\Models\Image;
use App\Models\Producto;
use App\Models\Slider;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SortController extends Controller
{

    public function categories(Request $request)
    {
        $position = 1;
        $sorts = $request->get('sorts');
        foreach ($sorts as $sort) {
            $category = Category::find($sort);
            $category->orden = $position;
            $category->save();
            $position++;
        }
    }

    public function subcategories(Request $request)
    {
        $position = 1;
        $sorts = $request->get('sorts');
        foreach ($sorts as $sort) {
            $category = Subcategory::find($sort);
            $category->orden = $position;
            $category->save();
            $position++;
        }
    }

    public function caracteristicas(Request $request)
    {
        $position = 1;
        $sorts = $request->get('sorts');
        foreach ($sorts as $sort) {
            $caracteristica = Caracteristica::find($sort);
            $caracteristica->orden = $position;
            $caracteristica->save();
            $position++;
        }
    }

    public function especificacions(Request $request)
    {
        $position = 1;
        $sorts = $request->get('sorts');
        $producto_id = $request->get('producto_id');
        foreach ($sorts as $sort) {
            Producto::find($producto_id)->especificacions()->updateExistingPivot($sort, [
                'orden' => $position,
            ]);
            $position++;
        }
    }

    public function sliders(Request $request)
    {
        $position = 1;
        $sorts = $request->get('sorts');
        foreach ($sorts as $sort) {
            $slider = Slider::find($sort);
            $slider->orden = $position;
            $slider->save();
            $position++;
        }
    }

    public function imagesproducto(Request $request)
    {
        $position = 1;
        $sorts = $request->get('sorts');
        // $producto_id = $request->get('producto_id');
        foreach ($sorts as $sort) {
            Image::where('id', $sort)->update([
                'orden' => $position,
            ]);
            // return Image::find($sort);

            // Producto::find($producto_id)->especificacions()->updateExistingPivot($sort, [
            //     'orden' => $position,
            // ]);
            $position++;
        }
    }
}
