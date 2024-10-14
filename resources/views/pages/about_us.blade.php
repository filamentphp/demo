@extends('.__base_main')

@section('content')


    <div class="page-wrapper">

        <main class="main">
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="{{route('index')}}"><i class="d-icon-home"></i></a></li>
                        <li>À propos de nous</li>
                    </ul>
                </div>
            </nav>
            <div class="page-header pl-4 pr-4" style="background-image: url(images/page-header/about-us.jpg)">
                <h3 class="page-subtitle font-weight-bold">Bienvenue chez Muster & Dikson</h3>
                <h1 class="page-title font-weight-bold lh-1 text-white text-capitalize">Nos Services</h1>
                <p class="page-desc text-white mb-0">Découvrez nos produits de beauté de qualité pour sublimer votre style.</p>
            </div>


            <div class="page-content mt-10 pt-10">
                <section class="about-section pb-10 appear-animate">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-4 mb-10 mb-lg-4">
                                <h5 class="section-subtitle lh-2 ls-md font-weight-normal">01. Ce Que Nous Faisons</h5>
                                <h3 class="section-title lh-1 font-weight-bold">Offrir des services parfaits et pratiques</h3>
                                <p class="section-desc">Nous fournissons des solutions personnalisées et innovantes pour répondre aux besoins de nos clients, en garantissant des services de haute qualité et un support fiable à chaque étape.</p>
                            </div>

                            <div class="col-lg-8 ">
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <div class="counter text-center text-dark">
                                            <span class="count-to" data-fromvalue="0" data-tovalue="34" data-duration="900" data-delimiter=",">50</span>
                                            <h5 class="count-title font-weight-bold text-body ls-md">Années d'Activité</h5>
                                            <p class="text-grey mb-0">Plus de 50 ans d'expertise dans l'industrie de la beauté.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="counter text-center text-dark">
                                            <span class="count-to" data-fromvalue="0" data-tovalue="50" data-duration="900" data-delimiter=",">5</span>
                                            <h5 class="count-title font-weight-bold text-body ls-md">Marques de Design</h5>
                                            <p class="text-grey mb-0">Nous collaborons avec 5 marques renommées pour des produits de qualité.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="counter text-center text-dark">
                                            <span class="count-to" data-fromvalue="0" data-tovalue="130" data-duration="900" data-delimiter=",">10</span>
                                            <h5 class="count-title font-weight-bold text-body ls-md">Membres de l'Équipe</h5>
                                            <p class="text-grey mb-0">Une équipe passionnée de 10 professionnels dédiés à votre satisfaction.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
                <!-- Fin de la Section À Propos -->

                <section class="store-section pb-10 appear-animate">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6 order-md-first mb-4">
                                <h5 class="section-subtitle lh-2 ls-md font-weight-normal mb-1">03. Notre Magasin</h5>
                                <h3 class="section-title lh-1 font-weight-bold">Attendez-vous à un soutien<br>incroyable</h3>
                                <p class="section-desc text-grey">
                                    Déjà des millions de personnes sont très satisfaites de ceci.<br>
                                    Ce constructeur de pages et le nombre ne fait qu'augmenter. La technologie<br>
                                    évolue, les exigences augmentent. Muster & Dikson a apporté.
                                </p>
                                <a href="{{route('index')}}" class="btn btn-dark btn-link btn-underline ls-m">Découvrez Notre Magasin<i class="d-icon-arrow-right"></i></a>
                            </div>
                            <div class="col-md-6 mb-4">
                                <figure>
                                    <img src="{{asset('images/front/factory.jpg')}}" alt="Notre Magasin" width="580" height="507" class="banner-radius" style="background-color: #DEE6E8;" />
                                </figure>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Fin de la Section Magasin -->

                <section class="customer-section pb-10 appear-animate">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-7 mb-4">
                                <figure>
                                    <img src="{{asset('images/front/factory2.jpg')}}" alt="Client Heureux" width="580" height="507" class="banner-radius" style="background-color: #BDD0DE;" />
                                </figure>
                            </div>
                            <div class="col-md-5 mb-4">
                                <h5 class="section-subtitle lh-2 ls-md font-weight-normal">02. Client Heureux</h5>
                                <h3 class="section-title lh-1 font-weight-bold">Fournir des produits à la mode et<br>qualifiés</h3>
                                <p class="section-desc text-grey">
                                    Déjà des millions de personnes sont très satisfaites de ceci.<br>
                                    Ce constructeur de pages et le nombre ne fait qu'augmenter. La technologie<br>
                                    évolue, les exigences augmentent. Muster & Dikson a apporté.
                                </p>
                                <a href="{{route('index')}}" class="btn btn-dark btn-link btn-underline ls-m">Visitez Notre Magasin<i class="d-icon-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Fin de la Section Client -->



{{--                <section class="brand-section grey-section pt-10 pb-10 appear-animate">--}}
{{--                    <div class="container mt-8 mb-10">--}}
{{--                        <h5 class="section-subtitle lh-2 ls-md font-weight-normal mb-1 text-center">04. Nos Clients</h5>--}}
{{--                        <h3 class="section-title lh-1 font-weight-bold text-center mb-5">Marques Populaires</h3>--}}
{{--                        <div class="owl-carousel owl-theme row cols-xl-6 cols-lg-5 cols-md-4 cols-sm-3 cols-2"--}}
{{--                             data-owl-options="{--}}
{{--                    'nav': false,--}}
{{--                    'dots': false,--}}
{{--                    'autoplay': true,--}}
{{--                    'margin': 20,--}}
{{--                    'responsive': {--}}
{{--                        '0': {--}}
{{--                            'items': 2--}}
{{--                        },--}}
{{--                        '576': {--}}
{{--                            'items': 3--}}
{{--                        },--}}
{{--                        '768': {--}}
{{--                            'items': 4--}}
{{--                        },--}}
{{--                        '992': {--}}
{{--                            'items': 5--}}
{{--                        },--}}
{{--                        '1200': {--}}
{{--                            'items': 6--}}
{{--                        }--}}
{{--                    }--}}
{{--                }">--}}
{{--                            <figure class="brand-wrap bg-white banner-radius">--}}
{{--                                <img src="images/brands/1.png" alt="Marque" width="180" height="100" />--}}
{{--                            </figure>--}}
{{--                            <figure class="brand-wrap bg-white banner-radius">--}}
{{--                                <img src="images/brands/2.png" alt="Marque" width="180" height="100" />--}}
{{--                            </figure>--}}
{{--                            <figure class="brand-wrap bg-white banner-radius">--}}
{{--                                <img src="images/brands/3.png" alt="Marque" width="180" height="100" />--}}
{{--                            </figure>--}}
{{--                            <figure class="brand-wrap bg-white banner-radius">--}}
{{--                                <img src="images/brands/4.png" alt="Marque" width="180" height="100" />--}}
{{--                            </figure>--}}
{{--                            <figure class="brand-wrap bg-white banner-radius">--}}
{{--                                <img src="images/brands/5.png" alt="Marque" width="180" height="100" />--}}
{{--                            </figure>--}}
{{--                            <figure class="brand-wrap bg-white banner-radius">--}}
{{--                                <img src="images/brands/6.png" alt="Marque" width="180" height="100" />--}}
{{--                            </figure>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}

{{--                <section class="team-section pt-8 mt-10 pb-10 mb-6 appear-animate">--}}
{{--                    <div class="container">--}}
{{--                        <h5 class="section-subtitle lh-2 ls-md font-weight-normal mb-1 text-center">04. Nos Leaders</h5>--}}
{{--                        <h3 class="section-title lh-1 font-weight-bold text-center mb-5">Rencontrez notre équipe</h3>--}}
{{--                        <div class="row cols-sm-2 cols-md-4">--}}
{{--                            <div class="member appear-animate" data-animation-options="{'name': 'fadeInLeftShorter'}">--}}
{{--                                <figure class="banner-radius">--}}
{{--                                    <img src="images/subpages/team1.jpg" alt="membre de l'équipe" width="280" height="280" style="background-color: #EEE;">--}}
{{--                                    <div class="overlay social-links">--}}
{{--                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>--}}
{{--                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>--}}
{{--                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>--}}
{{--                                    </div>--}}
{{--                                </figure>--}}
{{--                                <h4 class="member-name">Tomasz Treflerzan</h4>--}}
{{--                                <h5 class="member-job">PDG / Fondateur</h5>--}}
{{--                            </div>--}}
{{--                            <div class="member appear-animate" data-animation-options="{'name': 'fadeInLeftShorter', 'delay': '.3s'}">--}}
{{--                                <figure class="banner-radius">--}}
{{--                                    <img src="images/subpages/team2.jpg" alt="membre de l'équipe" width="280" height="280" style="background-color: #121A1F;">--}}
{{--                                    <div class="overlay social-links">--}}
{{--                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>--}}
{{--                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>--}}
{{--                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>--}}
{{--                                    </div>--}}
{{--                                </figure>--}}
{{--                                <h4 class="member-name">Dylan Chavez</h4>--}}
{{--                                <h5 class="member-job">Responsable du Support / Fondateur</h5>--}}
{{--                            </div>--}}
{{--                            <div class="member appear-animate" data-animation-options="{'name': 'fadeInLeftShorter', 'delay': '.4s'}">--}}
{{--                                <figure class="banner-radius">--}}
{{--                                    <img src="images/subpages/team3.jpg" alt="membre de l'équipe" width="280" height="280" style="background-color: #E8E7E3;">--}}
{{--                                    <div class="overlay social-links">--}}
{{--                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>--}}
{{--                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>--}}
{{--                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>--}}
{{--                                    </div>--}}
{{--                                </figure>--}}
{{--                                <h4 class="member-name">Viktoriia Demianenko</h4>--}}
{{--                                <h5 class="member-job">Designer</h5>--}}
{{--                            </div>--}}
{{--                            <div class="member appear-animate" data-animation-options="{'name': 'fadeInLeftShorter', 'delay': '.5s'}">--}}
{{--                                <figure class="banner-radius">--}}
{{--                                    <img src="images/subpages/team4.jpg" alt="membre de l'équipe" width="280" height="280" style="background-color: #465D7F;">--}}
{{--                                    <div class="overlay social-links">--}}
{{--                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>--}}
{{--                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>--}}
{{--                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>--}}
{{--                                    </div>--}}
{{--                                </figure>--}}
{{--                                <h4 class="member-name">Mikhail Hnatuk</h4>--}}
{{--                                <h5 class="member-job">Support</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}
                <!-- Fin de la Section Équipe -->
            </div>
        </main>
        <!-- End Main -->
    </div>

@endsection('content')
