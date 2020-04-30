@extends('layouts.layout')
@section("title", "Usuario")

@section('content')
    <h1>Nombre: {{$user->nombre}}</h1>
    <h1>Apellido paterno: {{$user->apellido_paterno}}</h1>
    <h1>Apellido materno: {{$user->usuario}}</h1>
@endsection



@section("scripts")
    @parent

    <script>
        $(function()
        {
            $("#frmCrear").validate();
        })
    </script>
@endsection