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
use Nwidart\Modules\Facades\Module;

class ApiController extends Controller
{
    public function productos(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select('id', 'name', 'slug', 'requireserie', 'marca_id', 'unit_id')
            ->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->with(['marca', 'unit', 'almacens']);

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
                            });
                        // ->orWhereHas('especificacions', function ($q) use ($term) {
                        //     $q->where('especificacions.name', 'ilike', '%' . $term . '%');
                        // });
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
                'image_url' => ($producto->image && pathURLProductImage($producto->image)) ? pathURLProductImage($producto->image) : null,
            ];
        });

        return response()->json($products);
    }
}
