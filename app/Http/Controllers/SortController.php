<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
            $category->order = $position;
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
            $category->order = $position;
            $category->save();
            $position++;
        }
    }
}
