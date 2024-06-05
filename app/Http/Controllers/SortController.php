<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
}
