<?php

use App\Enums\MovimientosEnum;
use App\Enums\StatusPayWebEnum;
use App\Http\Controllers\AuthController;
use App\Models\Cajamovimiento;
use App\Models\Category;
use App\Models\Moneda;
use App\Models\Openbox;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
use Modules\Marketplace\Entities\Order;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'dashboard'
])->get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');


if (Features::enabled(Features::registration())) {
    Route::get('/register', function () {
        return redirect()->route('login')->with('activeForm', 'register');
    })->name('register');
}


// AUTH WITH REDES SOCIALES
Route::get('/login/auth/{driver}/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/login/auth/{driver}/callback', [AuthController::class, 'callback'])->name('auth.callback');


Route::get('/', function () {
    $empresa = mi_empresa();
    $moneda = Moneda::default()->first();
    $pricetype = getPricetypeAuth($empresa);
    $sliders = Slider::activos()->disponibles()->orderBy('orden', 'asc')->get();

    if (auth()->user()) {
        $status_pendiente = StatusPayWebEnum::PENDIENTE->value;
        $orderspending = Order::where('status', $status_pendiente)->count();
        if ($orderspending) {
            $mensaje = "Usted tiene $orderspending ordenes pendientes. <a class='font-semibold' href='" . route('orders') . "?estado-pago=$status_pendiente' >Ir a pagar</a>";
            session()->flash('flash.banner', $mensaje);
        }
    }

    return view('welcome', compact('sliders', 'empresa', 'moneda', 'pricetype'));
});



// Route::get('/prueba', function () {
//     $query = DB::select("select (pg_database.datname) as name, pg_size_pretty(pg_database_size(pg_database.datname)) as size from pg_database  where pg_database.datname = '" . env('DB_DATABASE') . "'");
//     return $query;
// });

Route::get('/charts/resumen-movimientos', function () {

    $data = Cajamovimiento::select('openboxes.startdate', 'typemovement', DB::raw('SUM(totalamount) as total'))
        ->join('openboxes', 'cajamovimientos.openbox_id', '=', 'openboxes.id')
        ->groupBy('startdate', 'typemovement')
        ->where('cajamovimientos.sucursal_id', auth()->user()->sucursal_id)
        ->get();

    $labels = $data->pluck('startdate')->unique()->values()->all();
    $labels = collect($labels)->map(function ($item) {
        return formatDate($item, "DD MMMM Y");
    });
    $datasets = [
        [
            'label' => 'SOLES',
            'backgroundColor' => '#3e95cd',
            'data' => $data->where('typemovement', MovimientosEnum::INGRESO)
                ->pluck('total')->values()->all()
        ],
        [
            'label' => 'DÓLARES',
            'backgroundColor' => '#8e5ea2',
            'data' => $data->where('typemovement',  MovimientosEnum::EGRESO)
                ->pluck('total')->values()->all()
        ]
    ];

    return response()->json([
        'labels' => $labels,
        'datasets' => $datasets
    ]);

    // $data = Cajamovimiento::select('sucursals.name as sucursal', 'typemovement', DB::raw('SUM(totalamount) as total'))
    //     ->join('sucursals', 'cajamovimientos.sucursal_id', '=', 'sucursals.id')
    //     ->groupBy('sucursal', 'typemovement')
    //     ->get();

    // $labels = $data->pluck('sucursal')->unique()->values()->all();
    // $datasets = [
    //     [
    //         'label' => 'SOLES',
    //         'backgroundColor' => '#3e95cd',
    //         'data' => $data->where('typemovement', 'INGRESO')->pluck('total')->values()->all()
    //     ],
    //     [
    //         'label' => 'DÓLARES',
    //         'backgroundColor' => '#8e5ea2',
    //         'data' => $data->where('typemovement', 'EGRESO')->pluck('total')->values()->all()
    //     ]
    // ];

    // return response()->json([
    //     'labels' => $labels,
    //     'datasets' => $datasets
    // ]);
});
