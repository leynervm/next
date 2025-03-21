<?php

use App\Enums\DefaultConceptsEnum;
use App\Enums\PromocionesEnum;
use App\Models\Empresa;
use App\Models\Guia;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use CodersFree\Shoppingcart\Facades\Cart;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
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
        $notes .= '<p class="mb-1 text-sm text-left">' . $note->nodeValue . '</p>';
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

function mi_empresa($loadsucursals = false, $relations = [])
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
    }]);

    if ($loadsucursals) {
        $empresa->with(['sucursals']);
    }

    if (count($relations) > 0) {
        $empresa->with($relations);
    }

    $empresa = $empresa->take(1)->first();
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

    if ($empresa && $empresa->usarLista()) {
        $pricetypedefault = Pricetype::activos()->default()->limit(1)->first();
        $pricetype = $pricetypedefault;
        $authuser = auth()->user();
        if ($authuser) {
            $pricetypelogin = Pricetype::activos()->login()->limit(1)->first();
            $authuser->load(['client.pricetype']);

            if (!empty($pricetypelogin)) {
                $pricetype = $pricetypelogin;
            }

            if ($authuser->client && $authuser->client->pricetype && $authuser->client->pricetype->isActivo()) {
                $pricetypeAuth = $authuser->client->pricetype;
                if ($pricetypeAuth->id > $pricetype->id) {
                    // si es superior, tomar lista web de cliente
                    $pricetype = $pricetypeAuth;
                }
            }
            // dd($pricetype);
            // return $pricetype;
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
        if (file_exists(public_path('storage/images/productos/' . $filename))) {
            return asset('storage/images/productos/' . $filename);
        } else {
            return null;
        }
    }
}

function pathURLSlider($filename = null)
{
    if (!is_null($filename)) {
        if (file_exists(public_path('storage/images/slider/' . $filename))) {
            return asset('storage/images/slider/' . $filename);
        } else {
            return null;
        }
    } else {
        return null;
    }
}


/**
 * @imageBase64 Retorna una imágen convertida en Base64.
 * @param string $filename Nombre de la imágen a convertir.
 * @param string $pathdir Ruta del directorio de la imágen a convertir.
 * @return string imagen convertido en Base64
 */
function imageBase64($filename, $pathdir = 'app/public/images/company/')
{
    if (!is_null($filename)) {
        if (file_exists(storage_path($pathdir . $filename))) {
            // storage/images/company/
            return 'data:image/png;base64,' . base64_encode(file_get_contents(storage_path($pathdir . $filename)));
        }
    }
    return null;
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
        if (file_exists(public_path('storage/images/categories/' . $filename))) {
            return asset('storage/images/categories/' . $filename);
        } else {
            return null;
        }
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
    return ($promocion->isActivo() && $promocion->isDisponible() && $promocion->isAvailable()) ? $promocion : null;
}

/**
 * @getPriceDscto Obtener precio del producto aplicando descuento.
 * @param float $precioVenta Precio de venta sin aplicar descuentos
 * @param float $descuento El código del tipo de moneda al que desea convertir
 * @param integer $modo Modo de aplicar el descuento, Opcion:0 => Aplica sobre la diferencia del precio de venta - preciocompra, Otra opcion => aplica descuento directamente al precio de venta
 * @param $pricetype Instancia del modelo Pricetype cuando se usa en modo lista de precios
 * @return string valor convertido a la moneda enviada
 */
function getPriceDscto($amount, $dsct, $pricetype = null)
{
    $decimals = !empty($pricetype) ? $pricetype->decimals : 3;
    $precio = number_format($amount - (($amount * $dsct) / 100), $decimals, '.', '');
    if (!empty($pricetype) && $pricetype->rounded > 0) {
        return $precio = round_decimal($precio, $pricetype->rounded);
    }

    return number_format($precio, $decimals, '.', '');
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

            if ($promocion->isLiquidacion()) {
                $precio_venta = number_format($precio_real_compra, $decimals, '.', '');
            }

            if ($promocion->isDescuento() || $promocion->isLiquidacion()) {
                if ($rounded > 0) {
                    $precio_venta = round_decimal($precio_venta, $rounded);
                }
            }
        }

        return number_format($precio_venta, $decimals, '.', '');
    }

    return 0;
}

function getPrecioventa($producto, $pricetype = null)
{
    if (!empty($pricetype)) {
        $price = number_format($producto->{$pricetype->campo_table}, $pricetype->decimals, '.', '');
    } else {
        $price = number_format($producto->pricesale, 2, '.', '');
    }
    return (float) $price;
}

function getAmountCombo($promocion, $pricetype = null, $almacen_id = null)
{
    $total = 0;
    $total_normal = 0;
    $products = [];

    if (!empty($promocion) && $promocion->isCombo()) {
        $type = null;
        $is_stock_disponible = true;
        foreach ($promocion->itempromos as $itempromo) {
            if ($almacen_id) {
                $stockCombo = decimalOrInteger($itempromo->producto->almacens->find($almacen_id)->pivot->cantidad ?? 0);
            } else {
                // $stockCombo = null;
                $stockCombo = decimalOrInteger($itempromo->producto->almacens->sum('pivot.cantidad') ?? 0);
            }

            if ($stockCombo <= 0) {
                $is_stock_disponible = false;
            }

            $price = getPrecioventa($itempromo->producto, $pricetype);
            $pricenormal = $price;

            if ($itempromo->isDescuento()) {
                $price = getPriceDscto($price, $itempromo->descuento, $pricetype);
                $type = decimalOrInteger($itempromo->descuento) . '% DSCT';
            }
            if ($itempromo->isLiquidacion()) {
                $price = !empty($pricetype) ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy;
                $type = 'LIQUIDACIÓN';
            }

            if ($itempromo->isGratuito()) {
                $price = 0;
                $type = 'GRATIS';
            }

            if ($pricetype) {
                if ($pricetype->rounded > 0) {
                    $price = round_decimal($price, $pricetype->rounded);
                }
            }

            $total = $total + number_format($price, !empty($pricetype) ? $pricetype->decimals : 2, '.', '');
            $total_normal = $total_normal + number_format($pricenormal, !empty($pricetype) ? $pricetype->decimals : 2, '.', '');
            $products[] = [
                'producto_id' => $itempromo->producto_id,
                'producto_slug' => $itempromo->producto->slug,
                'name' => $itempromo->producto->name,
                'imagen' => $itempromo->producto->imagen ? pathURLProductImage($itempromo->producto->imagen->url) : null,
                'price' => $price,
                'pricebuy' => $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy,
                'precio_normal' => $pricenormal,
                'stock' => $stockCombo,
                'unit' => $itempromo->producto->unit ? $itempromo->producto->unit->name : '',
                'type' => $type,
                'itempromo_id' => $itempromo->id,
                'almacens' => $itempromo->producto->almacens
            ];
        }
        return response()->json([
            'total' => $total,
            'total_normal' => $total_normal,
            'stock_disponible' => $is_stock_disponible,
            'is_disponible' => empty(verifyPromocion($promocion)) ? false : true,
            'promocion' => $promocion,
            'unit' => $promocion->producto->unit ? $promocion->producto->unit->name : '',
            'products' => $products,
        ])->getData();
    } else {
        return null;
        // return response()->json(['total' => $total, 'products' => $products])->getData();
    }
}

function getCartRelations(string $instance, $onlyDisponibles = false)
{
    $pricetype = getPricetypeAuth();
    $cart = Cart::instance($instance)->content();
    // dd($cart);
    $shoppings = $cart->transform(function ($item) use ($pricetype) {
        $is_disponible = true;
        $promocion = null;
        $options = collect($item->options)->toArray();

        if ($item->model && !is_null($item->model)) {
            $item->model = Producto::withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(cantidad), 0)'));
            }])->with(['promocions' => function ($query) use ($item) {
                $query->with('itempromos.producto')->where('id', $item->options->promocion_id);
            }])->find($item->id);

            $promocion = count($item->model->promocions) > 0 ? $item->model->promocions->first() : null;
            $pricesale = $item->model->getPrecioVenta($pricetype); //Asi esta ok
            $options['stock_disponible'] = $item->model->stock;
            if (!$item->model->isVisible() || !$item->model->isPublicado()) {
                $is_disponible = false;
            }

            if (!empty($promocion)) {
                $combo = getAmountCombo($promocion);
                if ($combo) {
                    if (!$combo->stock_disponible || !$combo->is_disponible) {
                        $is_disponible = false;
                        $pricesale = $pricesale + $combo->total;
                    }
                    $options['carshoopitems'] = $combo->products;
                }

                if (empty(verifyPromocion($promocion))) {
                    $is_disponible = false;
                }

                if ($item->qty > ($promocion->limit - $promocion->salidas)) {
                    $is_disponible = false;
                }
                $options['is_disponible'] = $is_disponible;
            }
            // dd($item, $promocion, $pricesale);

            if ($item->model->stock <= 0 || $item->model->stock < $item->qty) {
                $is_disponible = false;
            }
            $options['promocion'] = $promocion;
            $options['is_disponible'] = $is_disponible;
            $options['stock_disponible'] = $item->model->stock;
            // $item->model = $producto;
            $item->price = $pricesale;
        } else {
            $options['promocion'] = null;
            $options['is_disponible'] = false;
            $options['stock_disponible'] = 0;
        }
        if (!array_key_exists('date', $options)) {
            $options['date'] = now('America/Lima');
        }

        // Cart::instance($instance)->update($item->rowId, [
        //     'price' => $item->price
        // ]);
        // Cart::instance($instance)->update($item->rowId, [
        //     'price' => $item->price,
        //     'options' => $options,
        // ]);
        $item->options = (object) $options;
        return $item;
    });
    // dd($cart);
    // dd($shoppings);
    // if (auth()->check()) {

    // }

    if ($onlyDisponibles) {
        return $shoppings->filter(fn($item) => $item->options->is_disponible === true)
            ->sortByDesc(fn($item) => [$item->options->is_disponible, $item->options->date]);
        // ->sortByDesc(fn($item) => $item->options->is_disponible);
    } else {
        return $shoppings->sortByDesc(fn($item) => [$item->options->is_disponible, $item->options->date]);
        // return $shoppings->sortByDesc(fn($item) => $item->options->is_disponible);
    }
    // dd($shoppings);
    // ->sortByDesc(fn($item) => $item->options->date);
}

function getAmountCart($shoppings)
{
    $total = 0;
    $subtotal = 0;
    if (count($shoppings) > 0) {
        //Cuando un itempromo is_disponible = false mostrar agotado y no sumar el precio del item
        $total = $shoppings->filter(fn($item) => $item->options->is_disponible == true)
            ->map(fn($item) => $item->qty * $item->price)->sum();
        $subtotal = $shoppings->filter(fn($item) => $item->options->is_disponible == true)
            ->map(fn($item) => $item->qty * $item->price)->sum();
    }
    // foreach ($shoppings as $item) {
    //     $total_item = 0;
    //     $subtotal_item = 0;
    //     if ($item->options->is_disponible) {
    //         $total_item = $item->price * $item->qty;
    //         $subtotal_item = $item->price * $item->qty;
    //     }

    //     $total = $total + $total_item;
    //     $subtotal = $subtotal + $subtotal_item;
    // }

    return response()->json([
        'total' => number_format($total, 2, '.', ''),
        'subtotal' => number_format($subtotal, 2, '.', '')
    ])->getData();
}

function encryptText($text, $length = null)
{
    $length = empty($length) ? config('services.hashids.length') : $length;
    $hashids = new Hashids(config('services.hashids.password'), $length);
    return $hashids->encode($text);
}

function decryptText($text, $length = null)
{
    $length = empty($length) ? config('services.hashids.length') : $length;
    $hashids = new Hashids(config('services.hashids.password'), $length);
    return $hashids->decode($text)[0];
}

function getNameTime($abrev)
{
    $name = '';
    switch ($abrev) {
        case 'D':
            $name = 'DÍAS';
            break;
        case 'M':
            $name = 'MESES';
            break;
        case 'MM':
            $name = 'MESES';
            break;
        case 'Y':
            $name = 'AÑOS';
            break;
        case 'YY':
            $name = 'AÑOS';
            break;
        default:
            $name = '';
            break;
    }
    return $name;
}


function getTextPromocion($value)
{
    return PromocionesEnum::getText($value);
}
