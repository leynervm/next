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

<div style="margin-top:10px; padding:20px">

<p style="font-size:14px; margin:10px 0" class="text-center ">
<b>Estimado/a</b> {{ $claimbook->name }}:
</p>

<p style="font-size:12px; margin:10px 0" class="text-start ">
Le informamos que hemos recibido y registrado su reclamo en nuestro Libro de Reclamos con fecha {{ formatDate($claimbook->date, "DD MMMM Y") }}. Apreciamos que se haya tomado el tiempo para compartir su experiencia con nosotros.
</p>

<p style="font-size:12px; margin:10px 0" class="text-start ">
Nuestro equipo de atención al cliente está revisando su caso y se pondrá en contacto con usted para brindarle una solución.
</p>

<p style="font-size:12px; margin:10px 0" class="text-start ">
Si tiene alguna pregunta o necesita más información mientras tanto, no dude en contactarnos a través de {{ $empresa->email }} o llamando al
@if (count($empresa->telephones) > 0)
<div style="margin:10px auto;" class="text-start">
@foreach ($empresa->telephones as $item)
<p style="display: inline-block; font-size:11px;" class="text-start">
{{ $loop->first ? '' : ' - ' }} TELEF.: {{ $item->phone }}</p>
@endforeach
</div>
@endif
</p>

<p style="font-size:12px; margin:10px 0" class="text-start ">
Agradecemos su paciencia y comprensión.
<br>
Atentamente,
</p>

<p style="font-size:10px; margin:0 10px" class="text-center ">
{{ $empresa->document }} 
<br>
{{ $empresa->name }}
<br>
{{ $empresa->direccion }}
<br>
{{ $empresa->ubigeo->departamento }}, {{ $empresa->ubigeo->provincia }},
{{ $empresa->ubigeo->distrito }}
</p>



{{-- <x-mail::button url="route('claimbook.print.pdf', $claimbook)" color="next" style="text-transform: uppercase;">
DESCARGAR PDF DEL RECLAMO
</x-mail::button> --}}
<div style="padding: 20px;">
<a class="btn btn-link" href="{{ route('claimbook.print.pdf', ['claimbook' => $claimbook]) }}">DESCARGAR PDF DEL RECLAMO</a>
</div>
</div>

<div style="display: block; background:#000; width:100%;">
<p style="font-size:10px;color: #FFF;padding: 10px;margin: 0" class="text-center">© {{ $empresa->name }} 2012 - Todos los derechos reservados.</p>
</div>
</div>


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