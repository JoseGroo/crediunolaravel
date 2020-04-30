@extends('layouts.layout')

@section("title", "Listado de intereses")

@section('content')
    {{ Form::open([ 'route' => ['sucursales.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-body">

                @include("intereses._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection
