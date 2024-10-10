<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>Muster Dikson</title>

    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Riode - Ultimate eCommerce Template">
    <meta name="author" content="D-THEMES">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/icons/favicon.png">

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


    <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/animate/animate.min.css')}}">

    <!-- Plugins CSS File -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/magnific-popup/magnific-popup.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/owl-carousel/owl.carousel.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/sticky-icon/stickyicon.css')}}">

    <!-- Main CSS File -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.min.css')}}">
</head>

<body class="contact-us">

    @include('main.__header')


    @yield('content')

    @include('__footer')

    <!-- Plugins JS File -->

    <script src="{{asset('/vendor/template/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/template/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('vendor/template/jquery.gmap/jquery.gmap.min.js')}}"></script>

    <!-- Main JS File -->
    <script src="{{asset('js/main.min.js')}}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key="></script>
    <script>

        /*
        Map Settings

            Find the Latitude and Longitude of your address:
                - https://www.latlong.net/
                - http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

        */

        // Map Markers
        var mapMarkers = [ {
            address: "New York, NY 10017",
            html: "<strong>New York Office<\/strong><br>New York, NY 10017",
            popup: true
        } ];

        // Map Initial Location
        var initLatitude = 40.75198;
        var initLongitude = -73.96978;

        // Map Extended Settings
        var mapSettings = {
            controls: {
                draggable: !window.Riode.isMobile,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                streetViewControl: true,
                overviewMapControl: true
            },
            scrollwheel: false,
            markers: mapMarkers,
            latitude: initLatitude,
            longitude: initLongitude,
            zoom: 11
        };

        var map = $( '#googlemaps' ).gMap( mapSettings );

        // Map text-center At
        var mapCenterAt = function ( options, e ) {
            e.preventDefault();
            $( '#googlemaps' ).gMap( "centerAt", options );
        }

    </script>

    @yield('script')
</body>


</html>
