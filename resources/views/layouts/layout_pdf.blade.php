@php
    $user = Auth::user();
@endphp


    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crediuno | Reporte </title>

    <style type="text/css">
        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 0.5cm 1cm 0cm 1cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #00467a;
            color: white;
            text-align: center;
            line-height: 30px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #2a0927;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        html,body{
            background: url({{ public_path('/images/crediuno.jpg') }})!important;
            background-repeat: no-repeat;
            background-size: 96% auto;
            background-position: center bottom;
        }

        .text-center {
            text-align: center;
        }

        .div-ticket{
            font-size: 40px;
        }
        .fw-bold{
            font-weight: bold;
        }

        .divider{
            border-top: 3px dashed #000;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .pt-5
        {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!--<header>
    <h1>Crediuno</h1>
</header>-->

<main>
    @yield('content')
</main>

<!--<footer>
    <h1>www.styde.net</h1>
</footer>-->


<!--<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(270, 730, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
</script>-->

<script type="text/php">
        if ( isset($pdf) ) {
            // OLD
            // $font = Font_Metrics::get_font("helvetica", "bold");
            // $pdf->page_text(72, 18, "{PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(255,0,0));
            // v.0.7.0 and greater
            $x = 270;
            $y = 730;
            $text = "{PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold");
            $size = 10;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>
</html>
