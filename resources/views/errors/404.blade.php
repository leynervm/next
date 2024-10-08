@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('image')
    <div class="w-full max-w-full h-96 md:h-[30rem]">
        <img class="block w-full h-full object-scale-down" src="{{ asset('images/errors/404.png') }}" alt="">
    </div>
@endsection
@section('message', __('Not Found'))
