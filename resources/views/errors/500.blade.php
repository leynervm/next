@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('image')
    <div class="w-full max-w-full h-96 md:h-[30rem]">
        <img class="block w-full h-full object-scale-down" src="{{ asset('images/errors/500.png') }}" alt="">
    </div>
@endsection
@section('message', __('Server Error'))
