
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="col-md-12 feed-activity-list">

                @foreach ($model as $item)
                    @php
                        $foto_perfil =  Storage::exists($item->foto) ? $item->foto : "public/user_profile/default.png";
                    @endphp
                    <div class="feed-element">
                        <div class="float-left m-r-sm">
                            <img class="rounded-circle" style="width: 60px;height: 60px;" src="{{ Storage::url($foto_perfil) }}">
                        </div>
                        <div class="media-body ">
                            <a href="{{ route('clientes.details', $item->cliente_id) }}" style="color: #000000;">
                                <div class="float-right">
                                    <h6 class="text-muted">Cliente {{ strtolower(\App\Enums\estatus_cliente::getDescription($item->estatus)) }}</h6>
                                </div>
                                <h4 class="m-0">
                                    #{{ $item->cliente_id }}
                                    -
                                    {{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}

                                    <span class="text-muted" style="font-size: 14px;">
                                        <i class="mdi mdi-map-marker"></i>
                                        {{ $item->estado }}, {{ $item->localidad }}, {{ $item->colonia }}, {{ $item->calle }}
                                    </span>

                                    @if(!empty($item->telefono_fijo))
                                        <span class="text-muted" style="font-size: 14px;">
                                            <i class="mdi mdi-deskphone"></i>
                                            {{ $item->telefono_fijo }}
                                        </span>
                                    @endif

                                    @if(!empty($item->telefono_movil))
                                        <span class="text-muted" style="font-size: 14px;">
                                            <i class="mdi mdi-cellphone"></i>
                                            {{ $item->telefono_movil }}
                                        </span>
                                    @endif
                                </h4>
                            </a>
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="text-muted m-0">
                                        <i class="mdi mdi-office-building"></i> {{ $item->sucursal }} | Saldo: @money_format($item->total_deuda)
                                    </h5>
                                </div>

                                <div class="col-md-2">
                                    @if(!$item->es_aval)
                                        <a href="#" class="btn btn-sm btn-info hacerlo-aval float-right" data-id="{{ $item->cliente_id }}">Hacerlo aval</a>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>
                @endforeach
            </div>
        </div>

        @include("layouts._pagination", ['model' => $model])
    </div>
@else
    <p>No se encontro ningun cliente con los filtros seleccionados.</p>
@endunless
