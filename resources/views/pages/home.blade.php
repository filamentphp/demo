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
{{--                <section class="top-collection mt-4 pt-6 mt-lg-8 pt-lg-10 pb-6">--}}
{{--                    <div class="container">--}}
{{--                        <h3 class="text-center font-weight-bold lh-1 ls-m mt-6 mb-5">Best Sellers</h3>--}}

{{--                        <div class="products-group split-line row mb-8 mb-lg-8 box-shadow-2 bg-white pt-8 gutter-no">--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/1-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/1-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Fashionable Watch</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                <ins class="new-price">$39.00-$51.00</ins>--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/2-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/2-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Black Watch</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                <ins class="new-price">$98.00-$99.00</ins>--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/3-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/3-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Men's Fashion Shoes</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $111.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/4-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/4-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Men's Sport Shoes</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                <ins class="new-price">$79.00</ins><del class="old-price">$119.00</del>--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/5-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/4-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Men's Fashion Shoes</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $200.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/4-2.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/5-1.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Men's White Shoes</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $180.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/6-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/6-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Fashion Men's Hood</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $39.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/7-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/7-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Women's Fashion Bag</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $39.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}
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
{{--                <section class="icon-boxes-section mb-lg-10 pb-4 pt-2">--}}
{{--                    <div class="container">--}}
{{--                        <div class="owl-carousel owl-theme row cols-md-3 cols-sm-2 cols-1" data-owl-options="{--}}
{{--                            'nav': false,--}}
{{--                            'dots': false,--}}
{{--                            'autoplay': true,--}}
{{--                            'autoplayTimeout': 4000,--}}
{{--                            'responsive': {--}}
{{--                                '0': {--}}
{{--                                    'items': 1--}}
{{--                                },--}}
{{--                                '576': {--}}
{{--                                    'items': 2--}}
{{--                                },--}}
{{--                                '768': {--}}
{{--                                    'autoplay': false,--}}
{{--                                    'items': 3--}}
{{--                                },--}}
{{--                                '992': {--}}
{{--                                    'items': 4--}}
{{--                                }--}}
{{--                            }--}}
{{--                        }">--}}
{{--                            <div class="icon-box text-center appear-animate" data-animation-options="{--}}
{{--                                'name': 'fadeInRightShorter',--}}
{{--                                'delay': '.4s'--}}
{{--                            }">--}}
{{--                                <i class="icon-box-icon d-icon-truck text-primary" style="font-size: 5rem"></i>--}}
{{--                                <div class="icon-box-content">--}}
{{--                                    <h4 class="icon-box-title">Free Shipping & Return</h4>--}}
{{--                                    <p class="d-inline-block" style="max-width: 27rem;">Get free delivery of your orders--}}
{{--                                        all--}}
{{--                                        over the world.--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="icon-box text-center appear-animate" data-animation-options="{--}}
{{--                                'name': 'fadeInRightShorter',--}}
{{--                                'delay': '.6s'--}}
{{--                            }">--}}
{{--                                <i class="icon-box-icon d-icon-service text-primary" style="font-size: 4rem"></i>--}}
{{--                                <div class="icon-box-content">--}}
{{--                                    <h4 class="icon-box-title">Customer Support</h4>--}}
{{--                                    <p class="d-inline-block" style="max-width: 27rem;">We provide convenient support of--}}
{{--                                        24/7 for our customers.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="icon-box text-center appear-animate" data-animation-options="{--}}
{{--                                'name': 'fadeInRightShorter',--}}
{{--                                'delay': '.8s'--}}
{{--                            }">--}}
{{--                                <i class="icon-box-icon d-icon-money text-primary" style="font-size: 4.2rem"></i>--}}
{{--                                <div class="icon-box-content">--}}
{{--                                    <h4 class="icon-box-title">Secured Payment</h4>--}}
{{--                                    <p class="d-inline-block" style="max-width: 27rem;">We fully guarantee our money--}}
{{--                                        back--}}
{{--                                        policy with no doubt.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="icon-box text-center appear-animate" data-animation-options="{--}}
{{--                                'name': 'fadeInRightShorter',--}}
{{--                                'delay': '1s'--}}
{{--                            }">--}}
{{--                                <i class="icon-box-icon d-icon-card text-primary" style="font-size: 5rem"></i>--}}
{{--                                <div class="icon-box-content">--}}
{{--                                    <h4 class="icon-box-title">Money Back Guarantee</h4>--}}
{{--                                    <p class="d-inline-block" style="max-width: 30rem;">Get our first gift - 20% off for--}}
{{--                                        your first ordered product.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}
{{--                <section class="featured-collection mt-8 pt-6 mt-lg-10 pt-lg-10 pb-10">--}}
{{--                    <div class="container">--}}
{{--                        <h3 class="text-center font-weight-bold lh-1 ls-m mt-4 mb-5">Our Featured</h3>--}}

{{--                        <div class="products-group split-line row box-shadow-2 bg-white gutter-no mb-6">--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/7-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/7-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Women's Fashion Bag</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $39.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/8-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/8-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Fashionable Men's Bag</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $39.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/9-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/9-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Long Handle Bag</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $39.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-4 col-6">--}}
{{--                                <div class="product-wrap">--}}
{{--                                    <div class="product text-center">--}}
{{--                                        <figure class="product-media">--}}
{{--                                            <a href="demo34-product.html">--}}
{{--                                                <img src="images/demos/demo34/products/10-1.jpg" alt="product"--}}
{{--                                                     width="300" height="338">--}}
{{--                                                <img src="images/demos/demo34/products/10-2.jpg"--}}
{{--                                                     class="product-hover-image" alt="product" width="300" height="338">--}}
{{--                                            </a>--}}
{{--                                            <div class="product-action-vertical">--}}
{{--                                                <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"--}}
{{--                                                   data-target="#addCartModal" title="Add to cart"><i--}}
{{--                                                        class="d-icon-bag"></i></a>--}}
{{--                                                <a href="#" class="btn-product-icon btn-wishlist"--}}
{{--                                                   title="Add to wishlist"><i class="d-icon-heart"></i></a>--}}
{{--                                            </div>--}}
{{--                                            <div class="product-action">--}}
{{--                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Quick--}}
{{--                                                    View</a>--}}
{{--                                            </div>--}}
{{--                                        </figure>--}}
{{--                                        <div class="product-details">--}}
{{--                                            <h3 class="product-name">--}}
{{--                                                <a href="demo34-product.html">Travel Fashion Backpack</a>--}}
{{--                                            </h3>--}}
{{--                                            <div class="product-price">--}}
{{--                                                $49.00--}}
{{--                                            </div>--}}
{{--                                            <div class="ratings-container">--}}
{{--                                                <div class="ratings-full">--}}
{{--                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                </div>--}}
{{--                                                <a href="demo34-product.html" class="rating-reviews">( 6 reviews )</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="row mb-2">--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <div class="banner mb-4" style="background-color: #F3D1C7;">--}}
{{--                                    <figure>--}}
{{--                                        <img src="images/demos/demo34/banners/5.jpg" alt="banner" width="580"--}}
{{--                                             height="149">--}}
{{--                                    </figure>--}}
{{--                                    <div class="banner-content y-50">--}}
{{--                                        <h3 class="banner-title font-weight-bold">New Accessories</h3>--}}
{{--                                        <a href="demo34-shop.html"--}}
{{--                                           class="btn btn-link btn-dark btn-underline text-uppercase">Shop Now<i--}}
{{--                                                class="d-icon-arrow-right"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <div class="banner mb-4" style="background-color: #222;">--}}
{{--                                    <figure>--}}
{{--                                        <img src="images/demos/demo34/banners/6.jpg" alt="banner" width="580"--}}
{{--                                             height="149">--}}
{{--                                    </figure>--}}
{{--                                    <div class="banner-content y-50">--}}
{{--                                        <h3 class="banner-title text-white font-weight-bold">Comfort Shoes</h3>--}}
{{--                                        <a href="demo34-shop.html"--}}
{{--                                           class="btn btn-link btn-white btn-underline text-uppercase">Shop Now<i--}}
{{--                                                class="d-icon-arrow-right"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}
{{--                <section class="blog-section container mt-4 mt-lg-10 pt-4 mb-0 mb-lg-6">--}}
{{--                    <h3 class="text-center font-weight-bold lh-1 ls-m pt-4 mb-5">Latest News</h3>--}}
{{--                    <div class="owl-carousel owl-theme owl-shadow-carousel row cols-lg-3 cols-sm-2 cols-1 pb-4"--}}
{{--                         data-owl-options="{--}}
{{--                        'items': 3,--}}
{{--                        'margin': 20,--}}
{{--                        'dots': false,--}}
{{--                        'loop': false,--}}
{{--                        'nav': false,--}}
{{--                        'autoplay': true,--}}
{{--                        'autoplayTimeout': 4000,--}}
{{--                        'responsive': {--}}
{{--                            '0': {--}}
{{--                                'items': 1--}}
{{--                            },--}}
{{--                            '576': {--}}
{{--                                'items': 2--}}
{{--                            },--}}
{{--                            '992': {--}}
{{--                                'items': 3--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }">--}}
{{--                        <div class="post post-image-gap appear-animate"--}}
{{--                             data-animation-options="{'name': 'fadeInRightShorter', 'delay': '.5s'}">--}}
{{--                            <figure class="post-media">--}}
{{--                                <a href="post-single.html">--}}
{{--                                    <img src="images/demos/demo34/blog/1.jpg" width="400" height="240" alt="post" />--}}
{{--                                </a>--}}
{{--                                <div class="post-calendar">--}}
{{--                                    <span class="post-day">19</span>--}}
{{--                                    <span class="post-month">JAN</span>--}}
{{--                                </div>--}}
{{--                            </figure>--}}
{{--                            <div class="post-details">--}}
{{--                                <h4 class="post-title"><a href="post-single.html">30% Discount for Shoes & Bags</a></h4>--}}
{{--                                <p class="post-content">Sed pretium, ligula sollicitudin laoreet viverra, tortor libero--}}
{{--                                    sodales leo, eget blandit nunc tortor eu nibh. Suspendisse potenti.…</p>--}}
{{--                                <a href="post-single.html" class="btn btn-link btn-underline btn-primary btn-md">Read--}}
{{--                                    More<i class="d-icon-arrow-right"></i></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="post post-image-gap appear-animate"--}}
{{--                             data-animation-options="{'name': 'fadeInUpShorter', 'delay': '.3s'}">--}}
{{--                            <figure class="post-media">--}}
{{--                                <a href="post-single.html">--}}
{{--                                    <img src="images/demos/demo34/blog/2.jpg" width="400" height="240" alt="post" />--}}
{{--                                </a>--}}
{{--                                <div class="post-calendar">--}}
{{--                                    <span class="post-day">19</span>--}}
{{--                                    <span class="post-month">JAN</span>--}}
{{--                                </div>--}}
{{--                            </figure>--}}
{{--                            <div class="post-details">--}}
{{--                                <h4 class="post-title"><a href="post-single.html">Quisque a lectus</a></h4>--}}
{{--                                <p class="post-content">Sed pretium, ligula sollicitudin laoreet viverra, tortor libero--}}
{{--                                    sodales leo, eget blandit nunc tortor eu nibh. Suspendisse potenti.…</p>--}}
{{--                                <a href="post-single.html" class="btn btn-link btn-underline btn-primary btn-md">Read--}}
{{--                                    More<i class="d-icon-arrow-right"></i></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="post post-image-gap appear-animate"--}}
{{--                             data-animation-options="{'name': 'fadeInLeftShorter', 'delay': '.5s'}">--}}
{{--                            <figure class="post-media">--}}
{{--                                <a href="post-single.html">--}}
{{--                                    <img src="images/demos/demo34/blog/3.jpg" width="400" height="240" alt="post" />--}}
{{--                                </a>--}}
{{--                                <div class="post-calendar">--}}
{{--                                    <span class="post-day">19</span>--}}
{{--                                    <span class="post-month">JAN</span>--}}
{{--                                </div>--}}
{{--                            </figure>--}}
{{--                            <div class="post-details">--}}
{{--                                <h4 class="post-title"><a href="post-single.html">Utaliquam sollicitudin leo</a></h4>--}}
{{--                                <p class="post-content">Sed pretium, ligula sollicitudin laoreet viverra, tortor libero--}}
{{--                                    sodales leo, eget blandit nunc tortor eu nibh. Suspendisse potenti.…</p>--}}
{{--                                <a href="post-single.html" class="btn btn-link btn-underline btn-primary btn-md">Read--}}
{{--                                    More<i class="d-icon-arrow-right"></i></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}
            </div>

        </main>
        <!-- End Main -->
    </div>


@endsection('content')
