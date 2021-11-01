
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Server Error</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <!--
                <div class="code">
                    500
                </div>
            -->

            <div class="message" style="padding: 10px;">
                <p style="font-weight: bolder;">Server Error</p>
                <br>
                Detalles del error <span style="font-weight: bolder;">#{{ $log->log_error_id }}</span>:
                <br>
                <p><span style="font-weight: bolder;">Archivo:</span> {{ $log->file }}</p>
                <p><span style="font-weight: bolder;">Linea:</span> {{ $log->line }}</p>
                <p><span style="font-weight: bolder;">Código:</span> {{ $log->code }}</p>
                <p><span style="font-weight: bolder;">Mensaje:</span> {{ $log->message }}</p>
            </div>
        </div>
    </body>
</html>


