@extends('.__base')

@section('content')


    <div class="page-wrapper">
        <h1 class="d-none">Muster & Dikson</h1>

        <main class="main home">
            <div class="page-content">
                <section class="intro-section container mb-lg-4">
                    <div class="row pb-4">
                        <div class="intro-slider animation-slider owl-carousel owl-dot-white owl-theme owl-dot-inner row cols-1 gutter-no mb-4"
                             data-owl-options="{
                                    'items': 1,
                                    'loop': true,
                                    'autoplay': false
                                }">
                            <div class="intro-slide1 banner banner-fixed" style="background-color: #EEF2F5">
                                <figure>
                                    <img src="{{asset('images/front/hero.jpg')}}" alt="banner" width="1180" style="opacity: 0.5;"
                                         height="547" />
                                </figure>
                                <div class="banner-content y-50">
                                    <h4 class="banner-subtitle text-uppercase mb-3">L'Art de la Coiffure à Votre Domicile !</h4>
                                    <h3 class="banner-title font-weight-bold lh-1 ls-m mb-4">Équipez-vous des meilleurs<br> outils pour des résultats de salon.</h3>
                                    <p class="banner-desc font-weight-normal lh-1 mb-8">
{{--                                        <strong class="text-primary text-uppercase">Up To 10% Off</strong>--}}
                                        Transformez Votre Routine Beauté !
                                    </p>
                                    <a href="{{route('shop.muster')}}" class="btn btn-rounded btn-dark">Shop now<i
                                            class="d-icon-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="intro-slide2 banner banner-fixed" style="background-color: #EAE8ED">
                                <figure>
                                    <img src="{{asset('images/front/hero2.png')}}" alt="banner" width="1180" style="opacity: 0.6;"
                                         height="547" />
                                </figure>
                                <div class="banner-content y-50">
                                    <h4 class="banner-subtitle text-uppercase mb-3">From online Store</h4>
                                    <h3 class="banner-title font-weight-bold lh-1 ls-m mb-4">La Beauté à Portée <br> de Main !
                                    </h3>
{{--                                    <p class="banner-desc font-weight-normal lh-1 mb-8">--}}
{{--                                        <strong class="text-primary text-uppercase">Up To 10% Off</strong>--}}
{{--                                    </p>--}}
                                    <a href="{{route('shop.dikson')}}" class="btn btn-rounded btn-dark">Shop now<i
                                            class="d-icon-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="banner category-banner overlay-dark" style="background-color: #222;">
                                <figure>
                                    <img src="{{asset("images/front/img.png")}}" alt="banner" width="580" height="268" style="opacity: 0.4;">
                                </figure>
                                <div class="banner-content y-50">
{{--                                    <h4 class="banner-subtitle text-uppercase font-weight-normal lh-1 text-white">New--}}
{{--                                        Collection--}}
{{--                                    </h4>--}}
                                    <h3 class="banner-title font-weight-bold lh-1 mb-5 text-white">Voir Muster</h3>
                                    <a href="{{route('shop.muster')}}"
                                       class="btn btn-link btn-white btn-underline text-uppercase">Shop now<i
                                            class="d-icon-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="banner category-banner overlay-dark" style="background-color: #E5E6E8;">
                                <figure>
                                    <img src="{{asset("images/front/banner2.png")}}" alt="banner" width="580" height="268">
                                </figure>
                                <div class="banner-content y-50">
{{--                                    <h4 class="banner-subtitle text-uppercase font-weight-normal lh-1">Trending</h4>--}}
                                    <h3 class="banner-title font-weight-bold lh-1 mb-5 text-white">Voir Dikson</h3>
                                    <a href="{{route('shop.dikson')}}" class="btn btn-white btn-link btn-underline text-uppercase">Shop
                                        now<i class="d-icon-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="banner-section pt-8 pb-8 mt-4 pt-lg-10 pb-lg-10">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <div class="banner banner-sm" style="background-color: #CDCED3;">
                                    <figure>
                                        <img src="{{asset('images/front/newsletter.png')}}" alt="banner" width="380"
                                             height="170">
                                    </figure>
                                    <div class="banner-content y-50">
                                        <h4 class="banner-subtitle font-weight-bold ls-normal mb-0">Flash Sale</h4>
                                        <strong class="text-primary text-uppercase">Up To 50% Off</strong>
                                        <p class="banner-desc mb-0 ls-normal mt-4">From Riode Store</p>
                                    </div>
                                </div>
                                <div class="newsletter-form text-center">
                                    <h3 class="newsletter-title font-weight-bold text-uppercase ls-m mb-6">
                                        Newsletter</h3>
                                    <form action="#" method="get" class="input-wrapper">
                                        <input type="email" class="form-control text-center" name="email" id="email"
                                               placeholder="Email address here..." required="">
                                        <button class="btn btn-primary text-uppercase w-100"
                                                type="submit">Subscribe</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <div class="banner banner-lg" style="background-color: #544643;">
                                    <figure>
                                        <img style="opacity: 0.6" src="{{asset('images/front/img_1.png')}}" alt="banner" width="780"
                                             height="457">
                                    </figure>
                                    <div class="banner-content y-50">
                                        <h4
                                            class="banner-subtitle text-black text-uppercase font-weight-bold ls-normal mb-3">
                                            Chefs-d'œuvre pour les coiffeurs</h4>
                                        <h3 class="banner-title text-black font-weight-bold ls-normal lg-1">Ciseaux professionnels Müster</h3>
{{--                                        <p class="banner-desc text-white">Free shipping on all orders over $99.00</p>--}}
                                        <a href="{{ Storage::url('pdf/muster_scissors.pdf') }}" class="btn btn-white btn-rounded text-uppercase">Shop
                                            Now<i class="d-icon-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </main>
        <!-- End Main -->
    </div>


@endsection('content')
