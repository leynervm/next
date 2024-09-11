<?php

use App\Enums\MovimientosEnum;
use App\Enums\StatusPayWebEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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
use Nwidart\Modules\Facades\Module;

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

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/user/profile', function () {
    return redirect()->route('admin.profile');
});

if (Features::enabled(Features::registration())) {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
}

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

// Route::get('/prueba', function () {
//     $query = DB::select("select (pg_database.datname) as name, pg_size_pretty(pg_database_size(pg_database.datname)) as size from pg_database  where pg_database.datname = '" . env('DB_DATABASE') . "'");
//     return $query;
// });