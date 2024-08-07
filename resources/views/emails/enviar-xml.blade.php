<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
.body {
    background-color: #fff !important;
}

.content-cell {
    padding: 0 !important;
    border: 1px solid #edeff2;
}

.inner-body {
    width: 450px !important;
}

.content-ticket .table {
    padding-left: 10px !important;
    padding-right: 10px !important;
}

@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="400" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
{{-- {{ Illuminate\Mail\Markdown::parse($slot) }} --}}


<div class="content-ticket">
<div style="display: block; background:#000; width:100%; height: 100px;text-align: center; padding: 5px">
<img src="https://next.net.pe/img/logo.png" alt="Imagen de Bienvenida" style="max-width: 100%; width:auto; height: 100%; margin: auto;">
</div>

<div style="margin-top:10px;">
<h1 style="margin:0;font-size:16px;" class="text-center">{{ $comprobante->sucursal->empresa->name }}</h1>
<p style="margin:0;font-size:11px;" class="text-center">{{ $comprobante->sucursal->empresa->document }}</p>
<p style="margin:0;font-size:11px;" class="text-center">{{ $comprobante->sucursal->direccion }}</p>
<p style="margin:0;font-size:11px;" class="leading-3 text-center">
{{ $comprobante->sucursal->ubigeo->departamento }}, {{ $comprobante->sucursal->ubigeo->provincia }},
{{ $comprobante->sucursal->ubigeo->distrito }}</p>


@if (count($comprobante->sucursal->empresa->telephones) > 0)
<div style="margin:10px auto;" class="text-center">
@foreach ($comprobante->sucursal->empresa->telephones as $item)
<p style="display: inline-block; font-size:11px;" class="text-center">
{{ $loop->first ? '' : ' - ' }} TELEF.: {{ $item->phone }}</p>
@endforeach
</div>
@endif

<h1 style="margin:0;font-size:16px;" class="text-center">{{ $comprobante->seriecomprobante->typecomprobante->descripcion }}</h1>
<h1 style="margin:0;font-size:16px;" class="text-center">{{ $comprobante->seriecompleta }}</h1>



<p style="margin:0;font-size:11px;margin-top: 10px;"><b>FECHA EMISIÓN :</b>
{{ formatDate($comprobante->date, 'DD/MM/YYYY hh:mm A') }}</p>

<P style="margin:0;font-size:11px;"><b>CLIENTE : </b>{{ $comprobante->client->name }}</P>

<p style="margin:0;font-size:11px;"><b>TIPO Y N° DOC. :</b>
@if (strlen(trim($comprobante->client->document)) == 11)
REG.UNICO DE CONTRIBUYENTES (RUC)
@else
DOCUMENTO NACIONAL IDENTIDAD (DNI)
@endif
<br />
{{ $comprobante->client->document }}
</p>

<p style="margin:0;font-size:11px;"><b>TIPO PAGO : </b>{{ $comprobante->typepayment->name }}</p>
<p style="margin:0;font-size:11px;"><b>MONEDA : </b>{{ $comprobante->moneda->currency }}</p>


<x-mail::table>
|CANT. | DESCRIPCIÓN | P. UNIT | IMPORTE |
|:---- |:------------| -------:| -------:|
@foreach ($comprobante->facturableitems as $item)
| {{ formatDecimalOrInteger($item->cantidad) }} {{ $item->unit }} | {{ $item->descripcion }} | {{ number_format($item->price, 2, '.', ', ') }} | {{ number_format($item->subtotal, 2, '.', ', ') }} |
@endforeach
</x-mail::table>

<P style="margin:0;font-size:11px;" class="text-right">EXONERADO    : <b>{{ number_format($comprobante->exonerado, 2, '.', ', ') }}</b></P>
<P style="margin:0;font-size:11px;" class="text-right">GRAVADO      : <b>{{ number_format($comprobante->gravado, 2, '.', ', ') }}</b></P>
<P style="margin:0;font-size:11px;" class="text-right">IGV          : <b>{{ number_format($comprobante->igv + $comprobante->igvgratuito, 2, '.', ', ') }}</b></P>
<P style="margin:0;font-size:11px;" class="text-right">GRATUITO     : <b>{{ number_format($comprobante->gratuito, 2, '.', ', ') }}</b></P>
<P style="margin:0;font-size:11px;" class="text-right">TOTAL        : <b>{{ number_format($comprobante->total, 2, '.', ', ') }}</b></P>

<h1 style="font-size:12px; margin:10px 0" class="text-center">SON {{ $comprobante->leyenda }}</h1>


<p style="font-size:11px; margin:10px 0" class="text-center leading-3">
Representación impresa del comprobante de venta electrónico, este puede ser consultado en www.sunat.gob.pe. Autorizado mediante resolución N° 155-2017/Sunat.
</p>

<h1 style="font-size:12px; margin:10px 0" class="text-center leading-3">
BIENES TRANSFERIDOS EN LA AMAZONIA PARA SER CONSUMIDOS EN LA MISMA Y/O SERVICIOS PRESTADOS EN LA AMAZONIA.
</h1>

<p style="font-size:10px; margin:11px 0" class="text-center leading-3">
Estimado cliente, no hay devolución de dinero. Todo cambio de mercadería solo podrá realizarse dentro de las 48 horas, previa presentacion del comprobante.
</p>


@if ($comprobante->sucursal->empresa->web)
<x-mail::button :url="$comprobante->sucursal->empresa->web" color="next" style="text-transform: uppercase;">
{{ $comprobante->sucursal->empresa->web }}
</x-mail::button>
@endif

<h1 style="font-size:12px; margin:10px 0" class="text-center leading-3">GRACIAS POR SU COMPRA</h1>

<div style="display: block; background:#000; width:100%;">
<p style="font-size:10px;color: #FFF;padding: 10px;margin: 0" class="text-center">© Next Technologies EIRL 2012 - Todos los derechos reservados.</p>
</div>
</div>
</div>


</td>
</tr>
<tr>
<td>
<table class="content-buttons" width="100%" cellpadding="5" cellspacing="0" role="presentation">
<tr>
<td cellpadding="0" cellspacing="0" style="border: hidden !important;">          
<a class="btn btn-link" href="{{ route('facturacion.download.pdf', ['comprobante' => $comprobante, 'format'=> 'a4']) }}">DESCARGAR PDF</a>
</td>
<td cellpadding="0" cellspacing="0" style="border: hidden !important;">          
<a class="btn btn-link" href="{{ route('facturacion.download.xml', ['comprobante' => $comprobante, 'type'=> 'xml']) }}">DESCARGAR XML</a>
</td>
<td cellpadding="0" cellspacing="0" style="border: hidden !important;">          
<a class="btn btn-link" href="{{ route('facturacion.download.xml', ['comprobante' => $comprobante, 'type'=> 'cdr']) }}">DESCARGAR CDR</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>