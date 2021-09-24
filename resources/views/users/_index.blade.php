
@unless (empty($users))

   {{-- <div id="IndexList">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $item)
                            <tr>
                                <td>{{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                                <td>{{ $item->usuario }}</td>
                                <td>{{ $item->rol->rol }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $item ) }}" class="btn btn-sm btn-info">Editar</a>
                                    <a href="{{ route('users.views_details', $item) }}" class="btn btn-sm btn-white">Ver detalles</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include("layouts._pagination", ['model' => $users])
    </div>

    <div id="IndexList">
        <div class="row">
            <div class="col-md-12 feed-activity-list">

                @foreach ($users as $item)
                    @php
                        $foto_perfil =  Storage::exists($item->foto) ? $item->foto : "public/user_profile/default.png";
                    @endphp
                    <div class="feed-element">
                        <div class="float-left m-r-sm">
                            <img class="rounded-circle" style="width: 60px;height: 60px;" src="{{ Storage::url($foto_perfil) }}">
                        </div>
                        <div class="media-body ">
                            <div class="float-right">
                                <a href="{{ route('users.edit', $item ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('users.views_details', $item) }}" class="btn btn-sm btn-white">Ver detalles</a>
                            </div>
                            <h4 class="m-0">{{ $item->nombre }} {{ $item->apellido_paterno }} <span class="font-normal">({{ $item->usuario }})</span></h4>
                            <h5 class="text-muted m-0">{{ $item->rol->rol }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @include("layouts._pagination", ['model' => $users])
    </div>--}}

    <div id="IndexList">

        <div class="row">
                @foreach ($users as $item)
                    @php
                        $foto_perfil =  Storage::exists($item->foto) ? $item->foto : "public/user_profile/default.png";
                        //$foto_perfil =  File::exists($item->foto) ? $item->foto : "images/avatars/default.png";
                        $icon_color = $item->activo ? "font-active-status" : "font-inactive-status";
                        $activo = $item->activo ? "Usuario activo" : "Usuario inactivo";
                        $accion_delete = $item->activo ? "Desactivar" : "Reactivar";
                        $icon_accion = $item->activo ? "mdi-delete" : "mdi-rotate-left";

                    @endphp

                <div class="col-lg-4">
                    <div class="contact-box center-version">

                        <a href="{{ route('users.details', $item->id) }}">
                            <i title="" class="mdi mdi-circle mdi-24px {{ $icon_color }}" style="position: absolute;top: 28px;left: 179px;"></i>
                            <img alt="{{ $item->foto }}" class="rounded-circle" src="{{ Storage::url($foto_perfil) }}">
                            {{--<img alt="{{ $item->foto }}" class="rounded-circle" src="{{ asset($foto_perfil) }}">--}}
                            <h4 class="m-b-xs"><strong>{{ $item->nombre }} {{ $item->apellido_paterno }}</strong></h4>
                            <h5 class="m-b-xs">({{ $item->usuario }})</h5>
                            <div class="font-bold">{{ $item->rol }}</div>
                        </a>
                        <div class="contact-box-footer">
                            <div class="m-t-xs btn-group">
                                @if($item->activo)
                                    <a href="{{ route('users.edit', $item->id) }}" class="btn btn-xs btn-white"><i class="mdi mdi-pencil"></i> Editar </a>
                                    {{--<a href="{{ route('users.views_details', $item->id) }}" class="btn btn-xs btn-white"><i class="mdi mdi-folder"></i> Detalles</a>--}}

                                    <button type="button" user-id="{{ $item->id }}" usuario="{{ $item->nombre }} {{ $item->apellido_paterno }} ({{ $item->usuario }})" class="btn btn-xs btn-white cambiar-contrasena"><i class="mdi mdi-textbox-password"></i> Contraseña</button>
                                @endif
                                <button type="button" user-id="{{ $item->id }}" class="btn btn-xs btn-white delete"><i class="mdi {{ $icon_accion }}"></i> {{ $accion_delete }}</button>

                            </div>
                        </div>

                    </div>
                </div>

                @endforeach
        </div>

        @include("layouts._pagination", ['model' => $users])
    </div>
@else
    <p>No hay registros.</p>
@endunless