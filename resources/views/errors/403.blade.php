@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('image')
    <div class="w-full max-w-full h-96 md:h-[30rem]">
        <img class="block w-full h-full object-scale-down" src="{{ asset('images/403.png') }}" alt="">
    </div>
@endsection
@section('message', __($exception->getMessage() ?: 'Forbidden'))
