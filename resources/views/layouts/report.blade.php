<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>
        <style>
            body {
                margin: 70px 30px 30px;
                font-size: 13px
            }
            table td, table td * {
                vertical-align: top;
                padding: 2px 5px
            }
            table {
                border-collapse: collapse
            }
            header {
                position: fixed;
                top: 20px;
                left: 30px;
                right: 0cm;
                height: 2cm;
            }
            .clear {
                clear: both;
            }
            .page-break {
                page-break-after: always;
            }
        </style>
        @yield('header')
    </head>
    <body>

        <header>
            <div style="text-transform: uppercase; text-align: center; margin-bottom: 20px">
                <h3 style="margin:0!important">@yield('title')</h3>
                @yield('periode')<br>
            </div>
        </header>

        @yield('content')

            <script type="text/php">
                if (isset($pdf)) {
                    $text = "Page {PAGE_NUM}/{PAGE_COUNT}";
                    $size = 10;
                    $font = $fontMetrics->getFont("Verdana");
                    $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                    $x = ($pdf->get_width() - $width);
                    $y = $pdf->get_height() - 50;
                    $pdf->page_text($x, $y, $text, $font, $size);
                }
            </script>
        </div>
    </body>
</html>
