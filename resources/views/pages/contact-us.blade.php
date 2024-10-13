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

<div class="page-wrapper">
    <header class="header header-border">
        <div class="header-top">
            <div class="container">
                <div class="header-left">
                    <p class="welcome-msg">Bienvenue dans la boutique Muster & Dikson!</p>
                </div>
                <div class="header-right">
                    <span class="divider"></span>
                    <a href="{{route('contact')}}" class="contact d-lg-show"><i class="d-icon-map"></i>Contact</a>
                </div>
            </div>
        </div>
        <!-- End HeaderTop -->
        <div class="header-middle sticky-header fix-top sticky-content">
            <div class="container d-flex justify-content-center align-items-center">
{{--                <div class="d-flex justify-content-center align-items-center">--}}
                    <a href="#" class="mobile-menu-toggle">
                        <i class="d-icon-bars2"></i>
                    </a>
                    <a href="{{route('index')}}" class="logo">
                        <img src="{{asset('images/logo/logo.jpg')}}" alt="logo" width="253" height="84" />
                    </a>
            </div>
        </div>

        <div class="header-bottom d-lg-show">
            <div class="container">
                <div class="header-left">
                    <nav class="main-nav">
                        <ul class="menu">
                            <li>
                                <a href="{{route('index')}}">Home</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header -->
    <main class="main">
        <nav class="breadcrumb-nav">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="{{route('index')}}"><i class="d-icon-home"></i></a></li>
                    <li>Contactez-nous</li>
                </ul>
            </div>
        </nav>
        <div class="page-header" style="background-image: url(images/page-header/contact-us.jpg)">
            <h1 class="page-title font-weight-bold text-capitalize ls-l">Contactez-nous</h1>
        </div>
        <div class="page-content mt-10 pt-7">
            <section class="contact-section pb-4">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 ls-m mb-4">
                            <div class="grey-section d-flex align-items-center h-100">
                                <div>
                                    <h4 class="mb-2 text-capitalize">Adresse</h4>
                                    <p>N 15 rue Ennakhil cité dakhla Agadir</p>

                                    <h4 class="mb-2 text-capitalize">Tel:</h4>
                                    <p>
                                        <a href="tel:#">+212 662-750030</a><br>
                                    </p>

                                    <h4 class="mb-2 text-capitalize">Support:</h4>
                                    <p class="mb-4">
                                        <a href="#">contact@muster-dikson.ma</a><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 d-flex align-items-center mb-4">
                            <div class="w-100">
                                @if(session('success'))
                                    <div class="alert text-white alert-success alert-icon alert-inline mb-4">
                                        <i class="fas fa-check-circle"></i>
                                        <h4 class="alert-title">Votre message a été envoyé avec succès !</h4>
                                        <p>Merci. Nous vous répondrons dans les plus brefs délais.</p>
                                        <button type="button" class="btn btn-link btn-close">
                                            <i class="d-icon-times"></i>
                                        </button>
                                    </div>


                                    <div></div>
                                @endif

                                <form class="pl-lg-2" method="POST" action="{{ route('contact.store') }}">
                                    @csrf
                                    <h4 class="ls-m font-weight-bold">Écrivez-nous</h4>

                                    <div class="row mb-2">
                                        <div class="col-12 mb-4">
                                            <textarea class="form-control" id="message" name="message" placeholder="Message*" required></textarea>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <input class="form-control" type="text" id="nom" name="nom" placeholder="Nom *" required>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <input class="form-control" type="email" id="email" name="email" placeholder="Email *" required>
                                        </div>

                                        <div class="col-12 mb-4">
                                            <div class="form-control mt-4 mb-5 d-flex align-items-center">
                                                <input type="checkbox" class="custom-checkbox" id="entreprise-checkbox" name="entreprise">
                                                <label class="form-control-label ms-2" for="entreprise-checkbox">
                                                    Êtes-vous une entreprise ?
                                                </label>
                                            </div>
                                        </div>

                                        <div id="entreprise-fields" style="display: none;">
                                            <div class="col-md-6 mb-4">
                                                <input class="form-control" type="text" name="telephone" placeholder="Téléphone">
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <input class="form-control" type="text" name="ville" placeholder="Ville">
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <input class="form-control" type="text" name="nom_entreprise" placeholder="Nom de l'entreprise">
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <input class="form-control" type="text" name="num_pattente" placeholder="Numéro de patente">
                                            </div>
                                        </div>

                                    </div>

                                    <button class="btn btn-dark btn-rounded">Envoyer <i class="d-icon-arrow-right"></i></button>
                                </form>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const entrepriseCheckbox = document.getElementById('entreprise-checkbox');
                                            const entrepriseFields = document.getElementById('entreprise-fields');

                                            entrepriseCheckbox.checked = false;
                                            entrepriseCheckbox.addEventListener('change', function() {
                                                if (entrepriseCheckbox.checked) {
                                                    entrepriseFields.style.display = 'block';
                                                } else {
                                                    entrepriseFields.style.display = 'none';
                                                }
                                            });
                                        });
                                    </script>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End About Section-->

            <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
            <div class="grey-section google-map mt-5" id="googlemaps" style="height: 386px"></div>
            <!-- End Map Section -->
        </div>

    </main>
    <!-- End Main -->
    @include('__footer')

    <!-- End Footer -->
</div>
<!-- Scroll Top -->
<a id="scroll-top" href="#top" title="Top" role="button" class="scroll-top"><i class="d-icon-arrow-up"></i></a>


<!-- Plugins JS File -->
<script src="{{asset('vendor/template/jquery/jquery.min.js')}}"></script>
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
        address: "Agadir",
        html: "<strong>N 15 rue Ennakhil cité dakhla <\/strong><br>Agadir",
        popup: true
    } ];

    // Map Initial Location
    var initLatitude = 30.410198576249957;
    var initLongitude = -9.566071626367215;

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
</body>

</html>
