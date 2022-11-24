
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Crediuno | @yield("title")</title>


    <!--Favicon-->
    <!--<link rel="icon" type="image/png" sizes="16x16" href="@Url.Content("~/Public/images/favicon/favicon-16x16.png")">-->
    <!--<link rel="manifest" href="@Url.Content("~/Public/images/favicon/manifest.json")">-->
    <meta name="msapplication-TileColor" content="#ffffff">
    <!--<meta name="msapplication-TileImage" content="@Url.Content("~/Public/images/favicon/ms-icon-144x144.png")">-->
    <meta name="theme-color" content="#ffffff">

    <link rel="icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800,900&display=swap" rel="stylesheet">
    <!-- Styles -->


    @section("styles")
        @include("layouts._styles")
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
    @show

    @section("scripts")
        @include("layouts._scripts")
    @show

</head>
<body class="">
    <div id="wrapper">
        @include("layouts._menu")
        <div id="page-wrapper" class="gray-bg">

            @include('layouts._header')
            @include('layouts._title')

            <div class="wrapper wrapper-content animated fadeInRigh m-t-md">
                @include('general._messages')
                @yield('content')
            </div>
        </div>
    </div>
    <div class="modal inmodal" id="ReestructuraCredito" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Reestructura de prestamo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label('restructura_cliente_id', __('validation.attributes.cliente')) }}
                                {{ Form::select('restructura_cliente_id', [], null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                            </div>
                        </div>

                        <div class="col-12 mt-2" id="dataPrestamos">

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {

            $('#restructura_cliente_id').change(function(){
                var clienteId = $(this).val();

                if(clienteId <= 0){
                    $('#dataPrestamos').html('');
                    return;
                }

                $.ajax({
                    url: "{{route('prestamos.get_view_prestamos_by_cliente_id')}}",
                    dataType: "html",
                    type: "GET",
                    data: {
                        cliente_id: clienteId
                    },
                    cache: false,
                    success: function (data) {
                        $('#dataPrestamos').html(data);
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })

            $('#restructura_cliente_id').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                language: 'es',
                width:'100%',
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function (data) {
                    return data.html;
                },
                templateSelection: function (data) {
                    return data.text;
                },
                minimumInputLength: 1,
                placeholder: 'Busca un cliente',
                ajax: {
                    url: '{{ route('clientes.autocomplete_cliente_html') }}',
                    dataType: 'json',
                    method: 'POST',
                    delay: 250,

                    data: function (params) {
                        return {
                            term: params.term,
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                }
            });
        })
    </script>

    @include('layouts._loader')
</body>
</html>
