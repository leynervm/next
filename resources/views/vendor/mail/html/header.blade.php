@props(['url'])
@php
$logofooter = $empresa->logofooter;
$url_logo = getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false);
if (!empty($logofooter)) {
$url_logo = $empresa->getLogoFooterURL();
}
@endphp
<tr>
<td class="header {{ !empty($logofooter) ? 'header-secondary' : '' }}">
<a href="{{ $url }}" style="display: inline-block;">
@if (!empty($url_logo))
<img src="{{ ($url_logo) }}" class="logo" alt="{{ $empresa->name }}">
@else
{{ $slot }}
@endif
{{-- @if (trim($slot) === 'Laravel')
<img src="{{ getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false) }}" class="logo" alt="{{ $empresa->name }}">
@else
{{ $slot }}
@endif --}}
</a>
</td>
</tr>
