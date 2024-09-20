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


class ApiController extends Controller
{
    public function productos(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select('id', 'name', 'slug', 'requireserie', 'marca_id', 'unit_id')
            ->with('images', 'marca', 'unit', 'almacens');

        if (strlen(trim($search)) < 2) {
            $products->visibles()->orderBy('name', 'asc');
        } else {
            if (trim($search) !== '') {
                $searchTerms = explode(' ', $search);
                $products->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->orWhere('name', 'ilike', '%' . $term . '%')
                            ->orWhereHas('marca', function ($q) use ($term) {
                                $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $term . '%');
                            })
                            ->orWhereHas('category', function ($q) use ($term) {
                                $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $term . '%');
                            })
                            ->orWhereHas('especificacions', function ($q) use ($term) {
                                $q->where('especificacions.name', 'ilike', '%' . $term . '%');
                            });
                    }
                })->visibles()->orderBy('name', 'asc')->limit(10);
            }
        }

        $products = $products->get()->map(function ($producto) {
            return [
                'id' => $producto->id,
                'name' => $producto->name,
                'slug' => $producto->slug,
                'requireserie' => $producto->isRequiredserie(),
                'marca' => $producto->marca->name,
                'unit' => $producto->unit->name,
                'almacens' => $producto->almacens,
                'image_url' => $producto->getImageURL(),
            ];
        });

        return response()->json($products);
    }


    public function data(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select('id', 'name', 'slug');

        if (strlen(trim($search)) < 2) {
            $products->visibles()->orderBy('name', 'asc');
        } else {
            if (trim($search) !== '') {
                $searchTerms = explode(' ', $search);
                $products->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->orWhere('name', 'ilike', '%' . $term . '%');
                    }
                })->visibles()->orderBy('name', 'asc')->limit(10);
            }
        }

        $products = $products->get()->map(function ($producto) {
            return [
                'value' => $producto->id,
                'text' => $producto->name,
            ];
        });

        return response()->json($products);
    }

    public function marcas()
    {

        $marcas = Marca::whereHas('productos')->orderBy('name', 'asc');
        $marcas = $marcas->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response()->json($marcas);
    }

    public function categories()
    {

        $categories = Category::whereHas('productos')->orderBy('orden', 'asc');
        $categories = $categories->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response()->json($categories);
    }

    public function subcategories(Request $request)
    {

        $subcategories = [];
        $category_id = $request->input('category_id');

        if (!empty($category_id)) {
            $subcategories = Category::with(['subcategories' => function ($query) {
                $query->whereHas('productos')->orderBy('orden', 'asc');
            }])->find($category_id)->subcategories;
            $subcategories = $subcategories->map(function ($item) use ($category_id) {
                return [
                    'id' => $item->id,
                    'text' => $item->name,
                ];
            });
        }

        return response()->json($subcategories);
    }

    public function ubigeos()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')
            ->orderBy('distrito', 'asc');
        $ubigeos = $ubigeos->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  "$item->region / $item->provincia / $item->distrito / $item->ubigeo_reniec",
            ];
        });

        return response()->json($ubigeos);
    }

    public function almacens()
    {
        $almacens = Almacen::whereHas('productos')->orderBy('name', 'asc');
        $almacens = $almacens->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
            ];
        });

        return response()->json($almacens);
    }

    public function pricetypes()
    {
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        $pricetypes = $pricetypes->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
            ];
        });

        return response()->json($pricetypes);
    }

    public function typepayments()
    {
        $typepayments = Typepayment::activos()->orderBy('default', 'desc')->get();
        $typepayments = $typepayments->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
                'paycuotas' => $item->isCredito()
            ];
        });

        return response()->json($typepayments);
    }

    public function methodpayments()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $methodpayments = $methodpayments->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
            ];
        });

        return response()->json($methodpayments);
    }
}
