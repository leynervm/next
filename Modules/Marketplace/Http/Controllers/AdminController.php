<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\MethodPaymentOnlineEnum;
use App\Enums\StatusPayWebEnum;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Marketplace\Entities\Order;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.almacen.caracteristicas')->only('caracteristicas');
        $this->middleware('permission:admin.marketplace.orders|admin.marketplace.transacciones|admin.marketplace.userweb|admin.marketplace.trackingstates|admin.marketplace.shipmenttypes|admin.marketplace.sliders')->only('index');
        $this->middleware('can:admin.marketplace.sliders')->only('sliders');
        $this->middleware('can:admin.marketplace.shipmenttypes')->only('shipmenttypes');
        $this->middleware('can:admin.marketplace.transacciones')->only('transacciones');
        $this->middleware('can:admin.marketplace.userweb')->only('usersweb');
        $this->middleware('can:admin.marketplace.userweb.edit')->only('showuserweb');
        $this->middleware('can:admin.marketplace.trackingstates')->only('trackingstates');
    }

    public function index()
    {
        return view('marketplace::index');
    }

    public function caracteristicas()
    {
        return view('marketplace::caracteristicas.index');
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

    public function showuserweb(User $user)
    {
        $user->load(['direccions.ubigeo', 'client.pricetype']);
        return view('marketplace::admin.usersweb.show', compact('user'));
    }

    public function sliders()
    {
        return view('marketplace::admin.sliders.index');
    }

    // public function deposito(Request $request, Order $order,)
    // {
    //     $this->authorize('user', $order);
    //     $request->validate([
    //         'file' => 'required|image|mimes:jpg,jpeg,png|max:12288'
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         if ($request->file('file')) {

    //             $compressedImage = Image::make($request->file('file')->getRealPath())
    //                 ->resize(1000, 1000, function ($constraint) {
    //                     $constraint->aspectRatio();
    //                     $constraint->upsize();
    //                 })->orientate()->encode('jpg', 100);

    //             $url = uniqid() . '.' . $request->file('file')->getClientOriginalExtension();
    //             $compressedImage->save(public_path('storage/payments/depositos/' . $url));

    //             if ($compressedImage->filesize() > 1048576) { //10MB
    //                 $compressedImage->destroy();
    //                 return redirect()->back()->withErrors([
    //                     'file' => 'La imagen excede el tamaño máximo permitido.'
    //                 ])->withInput();
    //             }

    //             // $url = Storage::put('payments/depositos', $request->file('file'));
    //             $order->image()->create(['url' => $url]);
    //         }

    //         $order->methodpay = MethodPaymentOnlineEnum::DEPOSITO_BANCARIO->value;
    //         $order->status = StatusPayWebEnum::CONFIRMAR_PAGO;
    //         $order->save();
    //         DB::commit();
    //         return redirect()->route('orders.payment', $order)->with('info', 'Pago de orden registrado correctamente');;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }
}
