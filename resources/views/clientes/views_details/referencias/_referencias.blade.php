@if($model && !$model->isEmpty())
    <button id="agregarNuevaReferencia" class="btn-sm btn-success">Agregar nueva</button>
    <div class="table-responsive m-t-lg">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>@lang('validation.attributes.nombre')</th>
                <th>@lang('validation.attributes.apellido_paterno')</th>
                <th>@lang('validation.attributes.apellido_materno')</th>
                <th>@lang('validation.attributes.relacion')</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
                   @foreach ($model as $item)
                       <tr>
                           <td>{{ $item->nombre }}</td>
                           <td>{{ $item->apellido_paterno }}</td>
                           <td>{{ $item->apellido_materno }}</td>
                           <td>{{ $item->relacion }}</td>
                           <td>
                               <a class="btn btn-circle btn-icon btn-white ver-referencia" href="#" data-referencia-id="{{ $item->referencia_cliente_id }}"><i class="mdi mdi-folder-open"></i></a>
                               <a class="btn btn-circle btn-icon btn-info edit-referencia" href="#" data-referencia-id="{{ $item->referencia_cliente_id }}"><i class="mdi mdi-file-edit"></i></a>
                               {{--<a class="btn btn-circle btn-icon btn-danger delete-referencia" href="#" data-referencia-id="{{ $item->referencia_cliente_id }}"><i class="mdi mdi-delete"></i> </a>--}}
                           </td>
                       </tr>
                   @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No tiene referencias registradas, Haga <a href="#" id="agregarNuevaReferencia">click aqui</a> para empezar a agregar.</p>
@endunless
