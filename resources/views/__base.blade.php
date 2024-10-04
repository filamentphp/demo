<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

        <title>Muster & Dikson</title>

        <meta name="keywords" content="HTML5 Template" />
        <meta name="description" content="Muster & Dikson">
        <meta name="author" content="D-THEMES">

        <!-- Favicon -->
        {{-- <link rel="icon" type="image/png" href="images/icons/favicon.png"> --}}

        <script>
            WebFontConfig = {
                google: { families: [ 'Poppins:400,500,600,700' ] }
            };
            ( function ( d ) {
                var wf = d.createElement( 'script' ), s = d.scripts[ 0 ];
                wf.src = 'js/webfont.js';
                wf.async = true;
                s.parentNode.insertBefore( wf, s );
            } )( document );
        </script>

        <!-- Preload Font -->

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/animate/animate.min.css') }}">

        <link rel="preload" href="{{ asset('fonts/front/riode.ttf') }}" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="{{ asset('vendor/template/fontawesome-free/webfonts/fa-solid-900.woff2') }}" as="font" type="font/woff2"
              crossorigin="anonymous">
        <link rel="preload" href="{{ asset('vendor/template/fontawesome-free/webfonts/fa-brands-400.woff2') }}" as="font" type="font/woff2"
              crossorigin="anonymous">

        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/animate/animate.min.css') }}">

        <!-- Plugins CSS File -->
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/magnific-popup/magnific-popup.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/owl-carousel/owl.carousel.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/template/sticky-icon/stickyicon.css') }}">

        <!-- Main CSS File -->
{{--        <link rel="stylesheet" type="text/css" href="{{ asset('css/template/css/demo19.css') }}">--}}
        <link rel="stylesheet" type="text/css" href="{{ asset('css/template/css/style.css') }}">
    </head>

    <body class="home">

        @include('__head')


        @yield('content')

        @include('__footer')

        <!-- Plugins JS File -->
        <script src="{{asset('vendor/template/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('vendor/template/parallax/parallax.min.js')}}"></script>
        <script src="{{asset('vendor/template/elevatezoom/jquery.elevatezoom.min.js')}}"></script>
        <script src="{{asset('vendor/template/magnific-popup/jquery.magnific-popup.min.js')}}"></script>

        <script src="{{asset('vendor/template/owl-carousel/owl.carousel.min.js')}}"></script>
        <script src="{{asset('vendor/template/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
        <script src="{{asset('vendor/template/isotope/isotope.pkgd.min.js')}}"></script>
        <!-- Main JS File -->
        <script src="{{asset('js/front/main.min.js')}}"></script>
    </body>

</html>
