<?php

namespace App\Http\Controllers;

use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Methodpayment;
use App\Models\Openbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EchartController extends Controller
{
    public function typemovements()
    {

        try {
            $openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();

            $ingresos = Cajamovimiento::query()->select('methodpayments.name')
                ->leftJoin('methodpayments', 'methodpayments.id', '=', 'cajamovimientos.methodpayment_id')
                ->selectRaw("COALESCE(SUM(amount), 0) as total")
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('typemovement', MovimientosEnum::INGRESO->value)
                ->groupBy('methodpayments.name')->get();

            $egresos = Cajamovimiento::query()->select('methodpayments.name')
                ->leftJoin('methodpayments', 'methodpayments.id', '=', 'cajamovimientos.methodpayment_id')
                ->selectRaw("COALESCE(SUM(amount), 0) as total")
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('typemovement', MovimientosEnum::EGRESO->value)
                ->groupBy('methodpayments.name')->get();

            $typemovements = Cajamovimiento::query()->select('typemovement')
                // ->leftJoin('methodpayments', 'methodpayments.id', '=', 'cajamovimientos.methodpayment_id')
                ->selectRaw("COALESCE(SUM(amount), 0) as total")
                ->where('sucursal_id', auth()->user()->sucursal_id)
                // ->where('typemovement', MovimientosEnum::INGRESO->value)
                ->groupBy('typemovement')->orderByDesc('typemovement')->get()
                ->map(function ($item) {

                    $data = Cajamovimiento::query()->select('methodpayments.name')
                        ->leftJoin('methodpayments', 'methodpayments.id', '=', 'cajamovimientos.methodpayment_id')
                        ->selectRaw("COALESCE(SUM(amount), 0) as total")
                        ->where('sucursal_id', auth()->user()->sucursal_id)
                        ->where('typemovement',  $item->typemovement)
                        ->groupBy('methodpayments.name')->get()->map(function ($value) {
                            return [
                                $value->name,
                                decimalOrInteger($value->total, 2)
                            ];
                        });

                    return [
                        'value' =>  decimalOrInteger($item->total, 2),
                        'groupId' => $item->typemovement,
                        'data' => $data
                    ];
                });

            $data = Cajamovimiento::query()->select('typemovement', 'methodpayments.name as methodpayment')
                ->leftJoin('methodpayments', 'methodpayments.id', '=', 'cajamovimientos.methodpayment_id')
                ->selectRaw("COALESCE(SUM(amount), 0) as total")
                ->where('sucursal_id', auth()->user()->sucursal_id)
                // ->where('typemovement', MovimientosEnum::INGRESO->value)
                ->groupBy('typemovement', 'methodpayment')->orderByDesc('typemovement', 'methodpayment')->get();



            return response()->json([
                'success' => true,
                'typemovements' => $typemovements,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'openbox' => $openbox,
                'data' => $data,
                // 'datos' => $datos,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'mensaje' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'mensaje' => $e->getMessage()]);
            throw $e;
        }
    }
}
