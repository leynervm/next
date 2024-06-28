<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\MethodPaymentOnlineEnum;
use App\Enums\StatusPayWebEnum;
use App\Models\Category;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Subcategory;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Nwidart\Modules\Routing\Controller;

class MarketplaceController extends Controller
{

    public function index()
    {
        return view('marketplace::index');
    }

    public function ofertas()
    {

        $empresa = mi_empresa();
        $moneda = Moneda::default()->first();
        $pricetype = getPricetypeAuth($empresa);

        $ofertas = Producto::whereHas('promocions', function ($query) {
            $query->disponibles();
        })->paginate();
        return view('marketplace::ofertas', compact('ofertas', 'empresa', 'moneda', 'pricetype'));
    }

    public function orders()
    {
        return view('marketplace::orders.index');
    }

    public function create()
    {
        $empresa = mi_empresa();
        $moneda = Moneda::default()->first();
        $pricetype = getPricetypeAuth($empresa);

        return view('marketplace::orders.create',  compact('empresa', 'moneda', 'pricetype'));
    }

    public function payment(Order $order)
    {
        $this->authorize('user', $order);
        return view('marketplace::orders.payment', compact('order'));
    }

    public function deposito(Request $request, Order $order,)
    {
        $this->authorize('user', $order);
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:12288'
        ]);

        DB::beginTransaction();
        try {
            if ($request->file('file')) {

                $compressedImage = Image::make($request->file('file')->getRealPath())
                    ->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 100);

                $url = uniqid() . '.' . $request->file('file')->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/payments/depositos/' . $url));

                if ($compressedImage->filesize() > 1048576) { //10MB
                    $compressedImage->destroy();
                    $compressedImage->delete();
                    return redirect()->back()->withErrors([
                        'file' => 'La imagen excede el tamaño máximo permitido.'
                    ])->withInput();
                }

                // $url = Storage::put('payments/depositos', $request->file('file'));
                $order->image()->create(['url' => $url]);
            }

            $order->methodpay = MethodPaymentOnlineEnum::DEPOSITO_BANCARIO->value;
            $order->status = StatusPayWebEnum::CONFIRMAR_PAGO;
            $order->save();
            DB::commit();
            return redirect()->route('orders.payment', $order)->with('info', 'Pago de orden registrado correctamente');;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function productos(Request $request)
    {
        $empresa = mi_empresa();
        $moneda = Moneda::default()->first();
        $pricetype = getPricetypeAuth($empresa);
        // $searchTerms = $request['coincidencias'] ?? null;
        // if ($searchTerms) {
        // }

        return view('marketplace::productos.index', compact('empresa', 'moneda', 'pricetype'));
    }

    public function showproducto(Producto $producto)
    {
        // $stocksucursalsarray = DB::select("SELECT 
        // SUCURSALS.name,SUCURSALS.direccion,CONCAT(UBIGEOS.region , ', ',UBIGEOS.provincia, ', ', UBIGEOS.distrito )as lugar, sum(cantidad) as TOTAL 
        // FROM ALMACEN_SUCURSAL
        // INNER JOIN ALMACEN_PRODUCTO ON ALMACEN_PRODUCTO.almacen_id = ALMACEN_SUCURSAL.almacen_id
        // INNER JOIN SUCURSALS ON SUCURSALS.id = ALMACEN_SUCURSAL.sucursal_id
        // INNER JOIN UBIGEOS ON SUCURSALS.ubigeo_id = UBIGEOS.id
        // WHERE PRODUCTO_ID = ? GROUP BY name,direccion,lugar", [$producto->id]) ?? [];

        $stocksucursals = DB::table('almacen_sucursal')
            ->join('almacen_producto', 'almacen_producto.almacen_id', '=', 'almacen_sucursal.almacen_id')
            ->join('sucursals', 'sucursals.id', '=', 'almacen_sucursal.sucursal_id')
            ->join('ubigeos', 'sucursals.ubigeo_id', '=', 'ubigeos.id')
            ->select(
                'sucursals.name',
                'sucursals.direccion',
                DB::raw("CONCAT(ubigeos.region , ', ',ubigeos.provincia, ', ', ubigeos.distrito ) as lugar"),
                DB::raw('SUM(cantidad) as total')
            )
            ->where('almacen_producto.producto_id', $producto->id)
            ->groupBy('sucursals.name', 'sucursals.direccion', 'lugar')
            ->get();

        $empresa = mi_empresa();
        $moneda = Moneda::default()->first();
        $pricetype = getPricetypeAuth($empresa);
        $shipmenttypes = Shipmenttype::get();

        $producto->views = $producto->views + 1;
        $producto->save();

        $recents = Producto::whereNot('id', $producto->id)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->take(18)->get();
        $sugerencias = Producto::where('marca_id', $producto->marca_id)
            ->whereNot('id', $producto->id)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->take(18)->get();
        $similares = Producto::where('subcategory_id', $producto->subcategory_id)
            ->whereNot('id', $producto->id)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->take(18)->get();

        return view('marketplace::productos.show', compact('producto', 'stocksucursals', 'empresa', 'moneda', 'shipmenttypes', 'pricetype', 'recents', 'sugerencias', 'similares'));
    }

    public function carshoop()
    {
        $moneda = Moneda::default()->first();
        return view('marketplace::carrito', compact('moneda'));
    }

    public function wishlist()
    {
        $moneda = Moneda::default()->first();
        return view('marketplace::wishlist', compact('moneda'));
    }

    public function profile()
    {
        return view('marketplace::profile');
    }

    public function trackingstates()
    {
        return view('marketplace::admin.trackingstates.index');
    }

    public function transacciones()
    {
        return view('marketplace::admin.transacciones.index');
    }

    public function shipmenttypes()
    {
        return view('marketplace::admin.shipmenttypes.index');
    }

    public function usersweb()
    {
        return view('marketplace::admin.usersweb.index');
    }

    public function sliders()
    {
        return view('marketplace::admin.sliders.index');
    }


    public function search(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select('id', 'name', 'slug');

        if (strlen(trim($search)) < 2) {
            return response()->json([]);
        }

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
        })->publicados()->orderBy('name', 'asc')->limit(10);

        return response()->json($products->get());
    }

    public function searchsubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Category::find($category_id)->subcategories()->orderBy('name', 'asc')->get();
        return response()->json($subcategories);
    }
}
