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

    {{--@if($corte)
        {{ Form::open([ 'route' => ['cortes.download_pdf' ], 'method' => 'POST', 'id' => 'frmGeneratePdf' ]) }}
        <input type="hidden" name="corte_id" value="{{ $corte->corte_id }}">
        {{ Form::close() }}
    @endif--}}
@endsection


@section("scripts")
    @parent

    <script>

        $(function()
        {
            {{--@if($corte)
            Swal.fire({
                title: '¿Desea imprimir el ticket?',
                text: 'Se abrio correctamente su corte.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    $('#frmGeneratePdf').submit();
                }
            })
            @endif--}}
        })
    </script>
@endsection
