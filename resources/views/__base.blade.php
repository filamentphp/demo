<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <title>Muster & Dikson</title>

        <meta name="keywords" content="HTML5 Template" />
        <meta name="description" content="Creative Multi-Purpose eCommerce Template">
        <meta name="author" content="D-THEMES">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="images/icons/favicon.png">
        <!-- Preload Font -->
        <link rel="preload" href="fonts/riode.ttf?5gap68" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
              crossorigin="anonymous">
        <link rel="preload" href="vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
              crossorigin="anonymous">
        <script>
            WebFontConfig = {
                google: { families: [ 'Jost:300,400,500,600,700', 'Poppins:300,400,500,600,700,800' ] }
            };
            ( function ( d ) {
                var wf = d.createElement( 'script' ), s = d.scripts[ 0 ];
                wf.src = 'js/webfont.js';
                wf.async = true;
                s.parentNode.insertBefore( wf, s );
            } )( document );
        </script>

        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/animate/animate.min.css')}}">

        <!-- Plugins CSS File -->
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/magnific-popup/magnific-popup.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/owl-carousel/owl.carousel.min.css')}}">

        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/sticky-icon/stickyicon.css')}}">

        <!-- Main CSS File -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/demo34.min.css')}}">


    </head>

    <body class="home">

        @include('__header')

        @yield('content')

        @include('__footer')

        @include('__libsjs')

        @yield('scripts')

    </body>

</html>

