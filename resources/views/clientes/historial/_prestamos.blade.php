
@if($model && !$model->isEmpty())
    <div class="table-responsive m-t-lg">
        <table class="table table-hover">
            <thead>
            <tr>
                <th># Prestamo</th>
                <th>Tipo</th>
                <th>Periodo</th>
                <th>Duración</th>
                <th>Capital solicitado</th>
                <th>Fecha</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                   @foreach ($model as $item)
                       <tr>
                           <td>{{ $item->folio }}</td>
                           <td>{{ $item->tbl_interes->nombre }}</td>
                           <td>{{ \App\Enums\periodos_prestamos::getDescription($item->periodo) }}</td>
                           <td>{{ $item->duracion_text }}</td>
                           <td>@money_format($item->capital)</td>
                           <td>{{ \Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y h:i:s a') }}</td>
                           <td>
                               @if($item->tbl_prestamos_reestructurados->count() > 0)
                                <a href="#" class="btn btn-sm btn-white show-reestructuras" data-prestamo-id="{{$item->prestamo_id}}">Mostrar reestructuras</a>
                               @endif
                               <a href="{{ route('clientes.historial.estado_prestamo', $item->prestamo_id) }}" class="btn btn-sm btn-white">Detalles</a>
                           </td>
                       </tr>
                       @if($item->tbl_prestamos_reestructurados->count() > 0)
                           <tr class="childs-{{ $item->prestamo_id }}" style="display: none;">
                               <td colspan="7">
                                   @foreach($item->tbl_prestamos_reestructurados as $reestructura)
                                       <div class="row">
                                           <div class="col-md-3 col-sm-6 col-12">
                                               <div class="form-group">
                                                   <label>Periodo:</label>
                                                   <div class="form-control-plaintext">{{ \App\Enums\periodos_prestamos::getDescription($reestructura->periodo) }}</div>
                                               </div>
                                           </div>
                                           <div class="col-md-3 col-sm-6 col-12">
                                               <div class="form-group">
                                                   <label>Dia de pago:</label>
                                                   <div class="form-control-plaintext">{{ \App\Enums\dias_semana::getDescription($reestructura->dia_pago) }}</div>
                                               </div>
                                           </div>
                                           <div class="col-md-3 col-sm-6 col-12">
                                               <div class="form-group">
                                                   <label>Duración:</label>
                                                   <div class="form-control-plaintext">{{ $reestructura->duracion_text }}</div>
                                               </div>
                                           </div>
                                           <div class="col-md-3 col-sm-6 col-12">
                                               <div class="form-group">
                                                   <label>Importe:</label>
                                                   <div class="form-control-plaintext">@money_format($reestructura->importe)</div>
                                               </div>
                                           </div>
                                           <div class="col-md-3 col-sm-6 col-12">
                                               <div class="form-group">
                                                   <label>Fecha:</label>
                                                   <div class="form-control-plaintext">{{ \Carbon\Carbon::parse($reestructura->fecha_creacion)->format('d/m/Y h:i:s a') }}</div>
                                               </div>
                                           </div>
                                           <hr class="col-12">
                                       </div>
                                   @endforeach
                               </td>
                           </tr>
                       @endif
                   @endforeach
            </tbody>
        </table>
    </div>


@endunless
