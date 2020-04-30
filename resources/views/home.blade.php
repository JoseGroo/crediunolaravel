@extends('layouts.layout')
@php
    $user = Auth::user();
@endphp

@section("title", "Dashboard")

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">@yield('title')</div>

                <div class="card-body">
                    Binvenido {{ $user->nombre }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}
                </div>
            </div>
        </div>
    </div>
@endsection
