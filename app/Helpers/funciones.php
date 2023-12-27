<?php

use App\Models\Guia;
use Illuminate\Support\Facades\Storage;

function verificarCarpeta($path, $disk = 'public')
{

    if ($disk == 'local') {
        if (!Storage::directoryExists(storage_path('app/' . $path))) {
            Storage::disk($disk)->makeDirectory($path);
        }
    } else {
        if (!Storage::directoryExists($path)) {
            Storage::makeDirectory($path);
        }
    }

    return true;
}


function formatDecimalOrInteger($numero, $decimals = 2)
{
    return intval($numero) == floatval($numero) ? intval($numero) : number_format($numero, $decimals, '.', '');
}

function extraerMensaje($data)
{
    $mensaje = '';
    if (count($data)) {
        foreach ($data as $key => $value) {
            if ($value > 0) {
                $table = str_replace('_', ' ', $key);
                $mensaje .= $mensaje == '' ? $table : ", $table";
            }
        }
    }
    return $mensaje == '' ? '' : "($mensaje)";
}


function getValueNode($ruta, $node = 'DigestValue', $position = 0)
{
    $doc = new DOMDocument();

    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $doc->load($ruta);
    //===================rescatamos Codigo(HASH_CPE)==================
    $node_value = $doc->getElementsByTagName($node)->item($position)->nodeValue;
    return $node_value ?? null;
}

function getNotesNode($ruta, $node = 'Note')
{
    $notes = '';
    $doc = new DOMDocument();

    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $doc->load($ruta);

    $notes_value = $doc->getElementsByTagName($node);
    foreach ($notes_value as $note) {
        // $notes[] = $note->nodeValue;
        $notes .= '<p class="text-left mb-1 text-sm">' . $note->nodeValue . '</p>';
    }

    return $notes;
}

function toastJSON($title, $icon = 'success')
{
    return response()->json([
        'title' => $title,
        'icon' => $icon,
    ])->getData();
}


function getIndicadorTransbProg()
{
    return response()->json([
        'code' => Guia::IND_TRA_PROG,
        'name' => Guia::NAME_IND_TRA_PROG
    ])->getData();
}
function getIndicadorVehiculoML()
{
    return response()->json([
        'code' => Guia::IND_TRASL_VEHIC_CAT_ML,
        'name' => Guia::NAME_IND_TRASL_VEHIC_CAT_ML
    ])->getData();
}

function getIndicadorRetornoVehEnvaVacio()
{
    return response()->json([
        'code' => Guia::IND_RET_VEH_ENV_EMB_VAC,
        'name' => Guia::NAME_IND_RET_VEH_ENV_EMB_VAC
    ])->getData();
}

function getIndicadorRetornoVehVacio()
{
    return response()->json([
        'code' => Guia::IND_RET_VEH_VAC,
        'name' => Guia::NAME_IND_RET_VEH_VAC
    ])->getData();
}

function getIndicadorTotalDAMDS()
{
    return response()->json([
        'code' => Guia::IND_TOT_MERC_DAM_DS,
        'name' => Guia::NAME_IND_TOT_MERC_DAM_DS
    ])->getData();
}

function getIndicadorRegistrarVehCondTransport()
{
    return response()->json([
        'code' => Guia::IND_REG_VEHIC_COND_TRANSP,
        'name' => Guia::NAME_IND_REG_VEHIC_COND_TRANSP
    ])->getData();
}



function getCarrito()
{
    $carrito = Session::get('carrito', []);
    if (!is_array($carrito)) {
        $carrito = json_decode($carrito, true);
    }

    return $carrito;
}


function getTotalCarrito($sesionName)
{

    $total = 0;
    $gratuito = 0;

    $carrito = Session::get($sesionName, []);
    if (!is_array($carrito)) {
        $carrito = json_decode($carrito, true);
    }

    if (count($carrito) > 0) {
        $carritoJSON = json_decode(Session::get($sesionName));
        foreach ($carritoJSON as $item) {
            if ($item->gratuito) {
                $gratuito += $item->importe;
            } else {
                $total += $item->importe;
            }
        }
    }

    return json_encode([
        'total' => formatDecimalOrInteger($total, 3),
        'gratuito' => formatDecimalOrInteger($gratuito, 3),
        'sumatoria' => formatDecimalOrInteger($total + $gratuito, 3),
    ]);

    // $total = Carshoop::Micarrito()
    //     ->where('sucursal_id', $this->sucursal->id)
    //     ->where('gratuito', 0)->sum('total') ?? 0;
    // $this->gratuito = Carshoop::Micarrito()
    //     ->where('sucursal_id', $this->sucursal->id)
    //     ->where('gratuito', 1)->sum('total') ?? 0;

}

