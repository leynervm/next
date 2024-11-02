<?php

use App\Enums\DefaultConceptsEnum;
use App\Models\Empresa;
use App\Models\Guia;
use App\Models\Pricetype;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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


function decimalOrInteger($numero, $decimals = 2, $separate = '')
{
    $valueInteger = empty($separate) ? intval($numero) : number_format($numero, 0, '.', $separate);
    return intval($numero) == floatval($numero) ? $valueInteger : number_format($numero, $decimals, '.', $separate);
}

function formatDate($date, $format = "DD MMMM YYYY hh:mm A")
{
    return !is_null($date) ? Str::upper(Carbon::parse($date)->locale('es')->isoformat($format)) : null;
}

function formatTelefono($numero, $group = 3, $prefijo = '')
{
    return $prefijo . chunk_split($numero, $group);
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

function getTiypemovimientos()
{
    return \App\Enums\MovimientosEnum::cases() ?? [];
}

function getStatusPayWeb()
{
    return \App\Enums\StatusPayWebEnum::cases() ?? [];
}


function verifyMonthbox($monthbox)
{
    return $monthbox->isUsing();
}


function verifyOpencaja($openbox)
{
    return $openbox->isActivo();
}

function mi_empresa()
{
    // $empresa = Empresa::query()->with(['image', 'telephones'])->select(
    //     'id',
    //     'document',
    //     'name',
    //     'direccion',
    //     'email',
    //     'web',
    //     'whatsapp',
    //     'facebook',
    //     'instagram',
    //     'tiktok',
    //     'icono',
    //     'uselistprice',
    //     'viewpriceantes',
    //     'viewlogomarca',
    //     'viewtextopromocion',
    //     'markagua',
    //     'viewpricedolar',
    //     'tipocambio',
    //     'sendmode',
    //     'afectacionigv',
    //     'limitsucursals'
    // )->first();
    $empresa = Empresa::query()->select('*')->addSelect(['logo' => function ($q) {
        $q->select('url')->from('images')->whereColumn('images.imageable_id', 'empresas.id')
            ->where('images.imageable_type', Empresa::class)
            ->orderBy('id', 'desc')->limit(1);
    }])->first();
    return $empresa;
}


function getTextConcept($value)
{
    switch ($value) {
        case  DefaultConceptsEnum::VENTAS->value;
            $text = 'VENTA FÍSICA';
            break;
        case DefaultConceptsEnum::INTERNET->value;
            $text = 'PAGO INTERNET';
            break;
        case DefaultConceptsEnum::PAYCUOTA->value;
            $text = 'PAGO CUOTA';
            break;
        case DefaultConceptsEnum::COMPRA->value;
            $text = 'COMPRA';
            break;
        case DefaultConceptsEnum::PAYCUOTACOMPRA->value;
            $text = 'PAGO CUOTA COMPRA';
            break;
        case DefaultConceptsEnum::PAYEMPLOYER->value;
            $text = 'PAGO MENSUALIDAD PERSONAL';
            break;
        case DefaultConceptsEnum::ADELANTOEMPLOYER->value;
            $text = 'ADELANTO PAGO PERSONAL';
            break;
        default:
            $text = null;
            break;
    }

    return $text;
}

function getMessageOpencaja()
{
    return response()->json([
        'title' => 'APERTURAR NUEVA CAJA DIARIA !',
        'text' => "La apertura de caja no se encuentra disponible en este momento, o ha sido cerrada."
    ])->getData();
}

function getMessageMonthbox()
{
    return response()->json([
        'title' => 'APERTURAR NUEVA CAJA MENSUAL !',
        'text' => "No se encontraron cajas mensuales aperturadas para registrar movimientos."
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

function getCombo()
{
    $combo = Session::get('combo', []);
    return collect($combo);
}


function getTotalCarrito($sesionName)
{

    $total = 0;
    $gratuito = 0;
    $countgratuitos = 0;
    $countnogratuitos = 0;

    $carrito = Session::get($sesionName, []);
    if (!is_array($carrito)) {
        $carrito = json_decode($carrito, true);
    }

    if (count($carrito) > 0) {
        $carritoJSON = json_decode(Session::get($sesionName));
        foreach ($carritoJSON as $item) {
            if ($item->gratuito) {
                $gratuito += $item->importe;
                $countgratuitos++;
            } else {
                $total += $item->importe;
                $countnogratuitos++;
            }
        }
    }

    return json_encode([
        'total' => decimalOrInteger($total, 3),
        'gratuito' => decimalOrInteger($gratuito, 3),
        'sumatoria' => decimalOrInteger($total + $gratuito, 3),
        'countgratuitos' => $countgratuitos,
        'countnogratuitos' => $countnogratuitos,
        'count' => $countgratuitos + $countnogratuitos,
    ]);

    // $total = Carshoop::Micarrito()
    //     ->where('sucursal_id', $this->sucursal->id)
    //     ->where('gratuito', 0)->sum('total') ?? 0;
    // $this->gratuito = Carshoop::Micarrito()
    //     ->where('sucursal_id', $this->sucursal->id)
    //     ->where('gratuito', 1)->sum('total') ?? 0;
}


function round_decimal($value, $roundedTo = 1)
{
    $result = $value - floor($value);
    $rounded = $roundedTo == 1 ? 0.5 : 1;

    if ($result > 0) {
        return ($result <= 0.5) ? (float) (floor($value) + $rounded) : round($value);
    } else {
        return (float) $value;
    }
}


function round_up($value, $places)
{
    $mult = pow(10, abs($places));
    return $places < 0 ? ceil($value / $mult) * $mult : ceil($value * $mult) / $mult;
}


function convertDolar($value, $tipocambio, $decimals = 2)
{
    if ($value > 0 && $tipocambio > 0) {
        return number_format($value / $tipocambio, $decimals, '.', '');
    }
    return null;
}

/**
 * @convertMoneda Convertir el valor de una moneda a otra.
 * @param float $amount El monto a convertir
 * @param string $code_moneda El código del tipo de moneda al que desea convertir
 * @param string $separate separador de miles
 * @return string valor convertido a la moneda enviada
 */
function convertMoneda(float $amount, string $code_moneda, float $tipo_cambio, int $decimals = 3, string $separate = '')
{
    if ($code_moneda == 'PEN') {
        $amount_converted = number_format($amount * $tipo_cambio, $decimals, '.', '');
    } else {
        $amount_converted = number_format($amount / $tipo_cambio, $decimals, '.', '');
    }

    return (string) number_format($amount_converted, $decimals, '.', $separate);
}

function getPricetypeAuth()
{

    $pricetype = null;
    $empresa = view()->shared('empresa');

    if ($empresa && $empresa->usarlista()) {
        $authuser = auth()->user();
        if ($authuser) {
            $authuser->load(['client.pricetype']);
            if ($authuser->client && $authuser->client->pricetype && $authuser->client->pricetype->isActivo()) {
                $pricetype = $authuser->client->pricetype;
            } else {
                $pricetype = Pricetype::login()->first();
            }

            //Si despues de validar lista no hay predeterminados
            // por defecto tomar el primero
            if (empty($pricetype)) {
                $pricetype = Pricetype::orderBy('id', 'asc')->limit(1)->first();
            }
        } else {
            $pricetype = Pricetype::default()->first();
        }
    }

    return $pricetype;
}

function getPriceAntes($precio_venta, $descuento, $pricetype = null, $separate = '')
{
    $precio_venta = number_format($precio_venta / ((100 - $descuento) / 100), $pricetype->decimals ?? 3, '.', '');
    if ($pricetype) {
        // if ($pricetype->rounded > 0) {
        //     $precio_venta = round_decimal($precio_venta, $pricetype->rounded);
        // }
        return decimalOrInteger($precio_venta, $pricetype->decimals, $separate);
    } else {
        return decimalOrInteger($precio_venta, 2, $separate);
    }
}

function getPriceIGV(float $amount, float $igv = 18)
{
    $price = number_format($amount * 100 / (100 + $igv), 3, '.', '');
    $json = response()->json([
        'price' => number_format($price, 3, '.', ''),
        'igv' => number_format($amount - $price, 3, '.', '')
    ]);

    return $json->getData();
}

function getPriceIncrement(float $amount, float $percent = 0)
{
    if ($percent > 0) {
        $amount = number_format($amount + (($amount * $percent) / 100), 3, '.', '');
    }

    return  number_format($amount, 3, '.', '');
}

function lista_precios()
{
    return Pricetype::pluck('campo_table')->toArray();
}

function toUTF8Import(string $value = null, $toUppercase = true, $especialcharacters = true)
{
    if (!is_null($value)) {
        if ($especialcharacters) {
            $value = str_replace("\u{A0}", '', $value);
        }

        if ($toUppercase) {
            return trim(mb_strtoupper($value, "UTF-8"));
        } else {
            return trim($value, "UTF-8");
        }
    } else {
        return null;
    }
}

function sanitizeWord($word)
{
    $replacements = [
        'ñ' => 'n',
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        'Á' => 'A',
        'É' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ú' => 'U',
        ' ' => '_',
        '-' => '_',
        '.' => '_',
        ',' => '_',
        ';' => '_',
        ':' => '_',
    ];
    return strtr($word, $replacements);
}


function pathURLProductImage($filename = null)
{
    if (is_null($filename)) {
        return asset('storage/images/productos/');
    } else {
        return asset('storage/images/productos/' . $filename);
    }
}


function getLogoEmpresa($filename = null, $forceHTTPS = true)
{
    if (!is_null($filename)) {
        return asset('storage/images/company/' . $filename, $forceHTTPS);
    }
    return null;
}

function getMarcaURL($filename = null)
{
    if (!is_null($filename)) {
        return asset('storage/images/marcas/' . $filename);
    }
    return null;
}

function getCategoryURL($filename = null)
{
    if (!is_null($filename)) {
        return asset('storage/images/categories/' . $filename);
    }
    return null;
}

function validarConfiguracionEmail()
{
    $emailSettings = [
        'MAIL_MAILER'    => Config::get('mail.default'),
        'MAIL_HOST'      => Config::get('mail.mailers.smtp.host'),
        'MAIL_PORT'      => Config::get('mail.mailers.smtp.port'),
        'MAIL_USERNAME'  => Config::get('mail.mailers.smtp.username'),
        'MAIL_PASSWORD'  => Config::get('mail.mailers.smtp.password'),
        'MAIL_ENCRYPTION' => Config::get('mail.mailers.smtp.encryption'),
        'MAIL_FROM_ADDRESS' => Config::get('mail.from.address')
    ];

    foreach ($emailSettings as $key => $value) {
        if (empty($value)) {
            return response()->json([
                'success' => false,
                'error' => "NO SE PUDO ENVIAR EL CORREO, CONFIGURACIÓN FALTANTE: {$key}"
            ]);
        }
    }
    return response()->json([
        'success' => true
    ]);
}

function getDscto($promocion)
{
    if (empty($promocion) || is_null($promocion)) {
        return null;
    }
    return ($promocion->isDescuento()) ? $promocion->descuento : null;
}

function verifyPromocion($promocion)
{
    if (empty($promocion) || is_null($promocion)) {
        return null;
    }
    return ($promocion->isDisponible() && $promocion->isAvailable()) ? $promocion : null;
}

/**
 * @getPriceDscto Obtener precio del producto aplicando descuento.
 * @param float $precioVenta Precio de venta sin aplicar descuentos
 * @param float $descuento El código del tipo de moneda al que desea convertir
 * @param integer $modo Modo de aplicar el descuento, Opcion:0 => Aplica sobre la diferencia del precio de venta - preciocompra, Otra opcion => aplica descuento directamente al precio de venta
 * @param $pricetype Instancia del modelo Pricetype cuando se usa en modo lista de precios
 * @return string valor convertido a la moneda enviada
 */
function getPriceDscto($amount, $dsct, $pricetype = null, $modo = 0,)
{
    $precio = number_format($amount - ($amount * ($dsct / 100)), 3, '.', '');
    if (!empty($pricetype) && $pricetype->rounded > 0) {
        return $precio = round_decimal($precio, $pricetype->rounded);
    }

    return number_format($precio, 3, '.', '');
}


/**
 * @getPriceDinamic Obtener precio de venta dinamicamnete en base al precio de compra.
 * @param float $pricebuy Precio de compra
 * @param float $incremento Porcentaje de incremento al precio de compra (por ejemplo, gastos adicionales).
 * @param float $ganancia Porcentaje de ganancia, se aplica al precio ya incrementado.
 * @param float $rounded Número de decimales para formatear el precio
 * @param float $decimals Número de decimales para redondear el precio final.
 * @param Promocion|null $promocion Instancia del modelo Promocion, si aplica alguna promoción.
 * @return float Valor de venta final como número decimal.
 */
function getPriceDinamic($pricebuy, $ganancia, $incremento = 0, $rounded = 0, $decimals = 2, $promocion = null)
{

    if ($pricebuy > 0) {
        $precio_real_compra = $incremento > 0 ? number_format($pricebuy + ($pricebuy * ($incremento / 100)), $decimals, '.', '') : $pricebuy;
        $precio_venta = $ganancia > 0 ? $precio_real_compra + ($precio_real_compra * ($ganancia / 100)) : $precio_real_compra;

        if ($rounded > 0) {
            $precio_venta = round_decimal($precio_venta, $rounded);
        }

        if ($promocion) {
            if ($promocion->isDescuento()) {
                $precio_venta = number_format($precio_venta - ($precio_venta * $promocion->descuento / 100), $decimals, '.', '');
            }

            if ($promocion->isRemate()) {
                $precio_venta = number_format($precio_real_compra, $decimals, '.', '');
            }

            if ($promocion->isDescuento() || $promocion->isRemate()) {
                if ($rounded > 0) {
                    $precio_venta = round_decimal($precio_venta, $rounded);
                }
            }
        }

        return number_format($precio_venta, $decimals, '.', '');
    }

    return 0;
}

function getAmountCombo($promocion, $pricetype = null, $almacen_id = null)
{
    $total = 0;
    $products = [];

    if (!empty($promocion) && $promocion->isCombo()) {
        $type = null;
        foreach ($promocion->itempromos as $itempromo) {
            if ($almacen_id) {
                $stockCombo = decimalOrInteger($itempromo->producto->almacens->find($almacen_id)->pivot->cantidad ?? 0);
            } else {
                $stockCombo = null;
            }

            $price = $pricetype ? $itempromo->producto->obtenerPrecioVenta($pricetype) : $itempromo->producto->pricesale;
            $pricenormal = $price;

            if ($itempromo->isDescuento()) {
                $price = getPriceDscto($price, $itempromo->descuento, $pricetype);
                $type = decimalOrInteger($itempromo->descuento) . '% DSCT';
            }
            if ($itempromo->isGratuito()) {
                $price = $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy;
                $type = 'GRATIS';
            }

            if ($pricetype) {
                if ($pricetype->rounded > 0) {
                    $price = round_decimal($price, $pricetype->rounded);
                }
            }

            $total = $total + number_format($price, !empty($pricetype) ? $pricetype->decimals : 2, '.', '');
            $products[] = [
                'producto_id' => $itempromo->producto_id,
                'name' => $itempromo->producto->name,
                'image' => $itempromo->producto->image ? pathURLProductImage($itempromo->producto->image) : null,
                'price' => $price,
                'pricebuy' => $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy,
                'pricenormal' => $pricenormal,
                'stock' => $stockCombo,
                'unit' => $itempromo->producto->unit->name,
                'type' => $type
            ];
        }
        return response()->json(['total' => $total, 'products' => $products])->getData();
    } else {
        return null;
        // return response()->json(['total' => $total, 'products' => $products])->getData();
    }
}
