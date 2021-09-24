@if($model && !$model->isEmpty())
    <button id="agregarNuevoDocumento" class="btn-sm btn-success">Agregar nuevo</button>
    <div class="table-responsive m-t-lg">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Tipo</th>
                <th>Documento</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
                   @foreach ($model as $item)
                       @php
                           $file =  Storage::exists($item->ruta) ? $item->ruta : "public/user_profile/default.png";
                           $nombre_documento = $item->clave_identificacion ? $item->documento.': '.$item->clave_identificacion : $item->documento;
                       @endphp
                       <tr>
                           <td>{{ \App\Enums\tipos_documento::getDescription($item->tipo) }}</td>
                           <td>{{ $nombre_documento }}</td>
                           <td>
                               <a class="fancybox btn btn-circle btn-icon btn-white" title="Ver archivo" href="{{ Storage::url($file) }}"><i class="mdi mdi-folder-open"></i></a>
                               <a class="btn btn-circle btn-icon btn-info edit-documento" href="#" data-documento-id="{{ $item->documento_cliente_id }}"><i class="mdi mdi-file-edit"></i></a>
                               {{--<a class="btn btn-circle btn-icon btn-danger delete-documento" href="#" data-documento-id="{{ $item->documento_cliente_id }}"><i class="mdi mdi-delete"></i> </a>--}}
                           </td>
                       </tr>
                   @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $('.fancybox').fancybox();
    </script>
@else
    <p>No tiene documentos registrados, Haga <a href="#" id="agregarNuevoDocumento">click aqui</a> para empezar a agregar.</p>
@endunless
