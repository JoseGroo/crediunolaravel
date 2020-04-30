@if($errors->any())

    <div class="alert alert-danger alert-dismissible" role="alert">

        <div class="row">
            <div class="col-12 m-b-sm">
                <strong style="font-size: 16px;">Ocurrio un error al intentar guardar. Detalles:</strong>
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">

        <div class="row">
            <div class="col-12 m-b-sm">
                <strong style="font-size: 16px;">Ocurrio un error al intentar guardar. Detalles:</strong>
            </div>

            <div class="col-12">
                {{ session('error') }}
            </div>
        </div>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif