@extends('layouts.layout')

@section("title", "Listado de divisas")


@section('content')
    {{ Form::open([ 'route' => ['divisas.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th>Divisa</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($model as $item)
                                <tr>
                                    <td>{{ $item->divisa }}</td>
                                    <td>
                                        <a href="{{ route('divisas.edit', $item->divisa_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    {{ Form::close() }}

@endsection
