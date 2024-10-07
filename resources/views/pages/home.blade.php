@extends('.__base')

@section('content')


    <div class="page-wrapper">

        <main class="main">
            <div class="page-content">
                <section class="intro-section">
                    <div class="owl-carousel owl-theme row owl-nav-fade intro-slider animation-slider cols-1 gutter-no"
                         data-owl-options="{
                            'nav': false,
                            'dots': false,
                            'loop': false,
                            'items': 1,
                            'autoplay': false,
                            'autoplayTimeout': 8000,
                            'responsive': {
                                '992': {
                                    'nav': true
                                }
                            }
                        }">
                        <div class="intro-slide1 banner banner-fixed" style="background-color: #f6f6f6;">
                            <figure>
                                <img src="https://d-themes.com/wordpress/riode/beauty/wp-content/uploads/sites/82/2021/01/slide-1.jpg" alt="intro-banner" width="1903"
                                     height="530" style="background-color: #f6f6f6;" />
                            </figure>
                            <div class="container">
                                <div class="banner-content y-50">
                                    <h4 class="banner-subtitle mb-4 slide-animate"
                                        data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">
                                        New Arrival
                                    </h4>
                                    <h2 class="banner-title slide-animate"
                                        data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                        Organic Cosmetics That Provide<br />Youth and Beauty</h2>
                                    <a href="demo-beauty-shop.html" class="btn btn-dark btn-rounded slide-animate"
                                       data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1s', 'delay': '1.8s'}">Shop
                                        Now<i class="d-icon-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="banner banner-fixed intro-slide2" style="background-color: #f6f6f6;">
                            <figure>
                                <img src="https://d-themes.com/wordpress/riode/beauty/wp-content/uploads/sites/82/2021/01/slide-2.jpg" alt="intro-banner" width="1903"
                                     height="530" style="background-color: #f6f6f6;" />
                            </figure>
                            <div class="container">
                                <div class="banner-content text-right y-50">
                                    <h4 class="banner-subtitle mb-4 ls-normal slide-animate"
                                        data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">
                                        Best Seller
                                    </h4>
                                    <h2 class="banner-title slide-animate"
                                        data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                        The Best Cosmetics <br /> for Your Hair and Skin</h2>
                                    <a href="demo-beauty-shop.html" class="btn btn-dark btn-rounded slide-animate"
                                       data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1s', 'delay': '1.8s'}">Shop
                                        Now<i class="d-icon-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
                <section class="service-section mt-6">
                    <div class="container appear-animate">
                        <div class="service-list">
                            <div class="service-carousel owl-carousel owl-theme row cols-lg-3 cols-sm-2 cols-1"
                                 data-owl-options="{
                                        'items': 3,
                                        'nav': false,
                                        'dots': false,
                                        'loop': true,
                                        'autoplay': true,
                                        'autoplayTimeout': 4000,
                                        'responsive': {
                                            '0': {
                                                'items': 1
                                            },
                                            '576': {
                                                'items': 2
                                            },
                                            '768': {
                                                'items': 3,
                                                'loop': false
                                            }
                                        }
                                    }">
                                <div class="icon-box icon-box-side icon-box1 appear-animate" data-animation-options="{
                                            'name': 'fadeInRightShorter',
                                            'delay': '.3s'
                                        }">
                                    <i class="icon-box-icon d-icon-truck mr-0 mr-lg-3" style="font-size: 46px;"></i>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Free Shipping &amp;
                                            Return
                                        </h4>
                                        <p class="mb-0">Livraison gratuite dès 300 MAD d’achat</p>
                                    </div>
                                </div>

                                <div class="icon-box icon-box-side icon-box2 appear-animate" data-animation-options="{
                                            'name': 'fadeInRightShorter',
                                            'delay': '.4s'
                                        }">
                                    <i class="icon-box-icon d-icon-service mr-0 mr-lg-3"></i>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Customer Support 24/7
                                        </h4>
                                        <p class="mb-0">Instant access to perfect support</p>
                                    </div>
                                </div>

                                <div class="icon-box icon-box-side icon-box3 appear-animate" data-animation-options="{
                                            'name': 'fadeInRightShorter',
                                            'delay': '.5s'
                                        }">
                                    <i class="icon-box-icon d-icon-secure mr-0 mr-lg-3"></i>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">100% Secure Payment
                                        </h4>
                                        <p class="mb-0">We ensure secure payment!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="container pt-8 mt-10 appear-animate"
                     data-animation-options="{'name': 'fadeInUpShorter', 'delay': '.3s'}">
                    <h2 class="title title-underline text-center">Best of the Week</h2>
                    <div class="owl-carousel owl-theme row cols-lg-4 cols-md-3 cols-2" data-owl-options="{
                                'items': 4,
                                'nav': false,
                                'dots': false,
                                'margin': 20,
                                'loop': false,
                                'responsive': {
                                    '0': {
                                        'items': 2
                                    },
                                    '768': {
                                        'items': 3
                                    },
                                    '992': {
                                        'items': 4
                                    }
                                }
                            }">
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/1.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/1-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" title="Select Options">
                                        <i class="d-icon-bag"></i>
                                    </a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">Bodycare Smooth Powder</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price">$199.00</span>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:40%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 3 reviews )</a>
                                </div>
                            </div>
                        </div>
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/2.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/2-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-label-group">
                                    <span class="product-label label-sale">27% off</span>
                                </div>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"
                                       data-target="#addCartModal" title="Add to cart">
                                        <i class="d-icon-bag"></i></a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">Bodycare Super Maker</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price">$999.00</span>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:60%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 1 reviews )</a>
                                </div>
                            </div>
                        </div>
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/3.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/3-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"
                                       data-target="#addCartModal" title="Add to cart">
                                        <i class="d-icon-bag"></i></a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">France Women Mixer</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price">$233.00</span>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:20%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 3 reviews )</a>
                                </div>
                            </div>
                        </div>
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/4.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/4-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-label-group">
                                    <span class="product-label label-sale">27% off</span>
                                </div>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" title="Select Options">
                                        <i class="d-icon-arrow-right"></i></a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">Round Cosmetia Powder</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price">$0.80-$2.00</span>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:100%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 6 reviews )</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container pt-4 mt-10 appear-animate"
                     data-animation-options="{'name': 'fadeIn', 'delay': '.3s'}">
                    <h2 class="title title-underline text-center mb-7">Popular Categories</h2>
                    <div class="row gutter-md category-grid">
                        <div class="height-x1">
                            <div
                                class="category category-banner category-absolute overlay-light overlay-zoom text-white">
                                <a href="#">
                                    <figure class="category-media">
                                        <img src="images/demos/demo-beauty/categories/1.jpg" alt="category" width="280"
                                             height="250" />
                                    </figure>
                                </a>
                                <div class="category-content">
                                    <h4 class="category-name">Rose Water</h4>
                                    <span class="category-count">
                                            <span>3</span> Products
                                        </span>
                                    <a href="demo-beauty-shop.html" class="btn btn-underline btn-link btn-white">Shop
                                        Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="height-x1 w-2">
                            <div class="category category-banner category-absolute overlay-dark overlay-zoom">
                                <a href="#">
                                    <figure class="category-media">
                                        <img src="images/demos/demo-beauty/categories/2.jpg" alt="category" width="480"
                                             height="250" />
                                    </figure>
                                </a>
                                <div class="category-content">
                                    <h4 class="category-name">Hand Cream</h4>
                                    <span class="category-count">
                                            <span>1</span> Products
                                        </span>
                                    <a href="demo-beauty-shop.html" class="btn btn-underline btn-link">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="height-x2">
                            <div
                                class="category category-banner category-absolute overlay-light overlay-zoom text-white">
                                <a href="#">
                                    <figure class="category-media">
                                        <img src="images/demos/demo-beauty/categories/3.jpg" alt="category" width="380"
                                             height="250" />
                                    </figure>
                                </a>
                                <div class="category-content">
                                    <h4 class="category-name">Toilet Powder</h4>
                                    <span class="category-count">
                                            <span>4</span> Products
                                        </span>
                                    <a href="demo-beauty-shop.html" class="btn btn-underline btn-link btn-white">Shop
                                        Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="height-x1 w-2">
                            <div class="category category-banner category-absolute overlay-dark overlay-zoom">
                                <a href="#">
                                    <figure class="category-media">
                                        <img src="images/demos/demo-beauty/categories/4.jpg" alt="category" width="480"
                                             height="250" />
                                    </figure>
                                </a>
                                <div class="category-content">
                                    <h4 class="category-name">Cosmetic Cream</h4>
                                    <span class="category-count">
                                            <span>5</span> Products
                                        </span>
                                    <a href="demo-beauty-shop.html" class="btn btn-underline btn-link">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="height-x1">
                            <div class="category category-banner category-absolute category5">
                                <a href="#">
                                    <figure class="category-media">
                                        <img src="images/demos/demo-beauty/categories/5.jpg" alt="category" width="280"
                                             height="250" />
                                    </figure>
                                </a>
                                <div class="banner-content w-100 x-50 y-50 text-center pl-2 pr-2">
                                    <h3 class="banner-title text-capitalize font-weight-bold">Our Brands</h3>
                                    <p class="mb-0 text-uppercase text-body">
                                        <a href="#">New York</a> - <a href="#">Paris</a> - <a href="#">Barcelona</a><br>
                                        <a href="#">Milan</a> - <a href="#">Rome</a> - <a href="#">London</a> - <a
                                            href="#">Dubai</a><br>
                                        <a href="#">Moscow</a> - <a href="#">Tokyo</a> - <a href="#">Shanghai</a><br>
                                        <a href="#">Mumbai</a> - <a href="#">Melbourne</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container pt-6 mt-10 text-center appear-animate"
                     data-animation-options="{'name': 'fadeIn', 'delay': '.3s'}">
                    <h2 class="title title-underline text-center">Recent Arrivals</h2>
                    <div class="owl-carousel owl-theme row cols-lg-4 cols-md-3 cols-2 mb-5" data-owl-options="{
                                'items': 4,
                                'nav': false,
                                'dots': false,
                                'margin': 20,
                                'loop': true,
                                'autoplay': true,
                                'autoplayTimeout': 4000,
                                'responsive': {
                                    '0': {
                                        'items': 2
                                    },
                                    '768': {
                                        'items': 3
                                    },
                                    '992': {
                                        'items': 4
                                    }
                                }
                            }">
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/5.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/5-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-label-group">
                                    <span class="product-label label-sale">27% off</span>
                                </div>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" title="Select Options">
                                        <i class="d-icon-bag"></i>
                                    </a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">Best Haircare</a>
                                </h3>
                                <div class="product-price">
                                    <ins class="new-price">$199.00</ins><del class="old-price">$210.00</del>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:40%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 3 reviews )</a>
                                </div>
                            </div>
                        </div>
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/6.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/6-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"
                                       data-target="#addCartModal" title="Add to cart">
                                        <i class="d-icon-bag"></i></a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">Pretty Lady Cosmetia</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price">$999.00</span>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:60%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 1 reviews )</a>
                                </div>
                            </div>
                        </div>
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/7.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/7-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" data-toggle="modal"
                                       data-target="#addCartModal" title="Add to cart">
                                        <i class="d-icon-bag"></i></a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">Cosmetic Perfect Tool</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price">$233.00</span>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:20%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 3 reviews )</a>
                                </div>
                            </div>
                        </div>
                        <div class="product text-center">
                            <figure class="product-media">
                                <a href="demo-beauty-product.html">
                                    <img src="images/demos/demo-beauty/products/8.jpg" alt="product" width="300"
                                         height="338">
                                    <img src="images/demos/demo-beauty/products/8-1.jpg" alt="product" width="300"
                                         height="338">
                                </a>
                                <div class="product-label-group">
                                    <span class="product-label label-sale">27% off</span>
                                </div>
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-cart" title="Select Options">
                                        <i class="d-icon-bag"></i></a>
                                    <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                            class="d-icon-heart"></i></a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                        View</a>
                                </div>
                            </figure>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="demo-beauty-product.html">American Woman Lipstick</a>
                                </h3>
                                <div class="product-price">
                                    <ins class="new-price">$199.00</ins><del class="old-price">$210.00</del>
                                </div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:100%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="demo-beauty-product.html" class="rating-reviews">( 6 reviews )</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="demo-beauty-shop.html" class="btn btn-outline btn-rounded btn-dark mb-4">View All
                        Products</a>
                </div>
                <section class="banner parallax mt-10 appear-animate" style="background-color: #1d1e20"
                         data-parallax-options="{'speed':2.5,'parallaxHeight':'150%','offset':-30}"
                         data-image-src="images/demos/demo-beauty/parallax.jpg">
                    <div class="container">
                        <div class="banner-content appear-animate" data-animation-options="{
                                'name': 'blurIn'
                            }">
                            <h4 class="banner-subtitle text-uppercase text-primary slide-animate"
                                data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">
                                Flash Sale 50% Off
                            </h4>
                            <h2 class="banner-title slide-animate font-weight-bold"
                                data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                Cosmetics For Summer Season</h2>
                            <a href="demo-beauty-shop.html"
                               class="btn btn-white btn-icon-right btn-rounded slide-animate"
                               data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1s', 'delay': '1.8s'}">Shop
                                Now<i class="d-icon-arrow-right"></i></a>
                        </div>
                    </div>
                </section>
                <section class="container mt-10 pt-4 appear-animate"
                         data-animation-options="{'name': 'fadeInLeftShorter', 'delay': '.3s'}">
                    <h2 class="title title-underline text-center">From Our Blogs</h2>
                    <div class="owl-carousel owl-theme owl-shadow-carousel row cols-lg-3 cols-sm-2 cols-1"
                         data-owl-options="{
                        'items': 3,
                        'margin': 20,
                        'dots': true,
                        'loop': false,
                        'nav': false,
                        'responsive': {
                            '0': {
                                'items': 1
                            },
                            '576': {
                                'items': 2
                            },
                            '992': {
                                'items': 3,
                                'dots': false
                            }
                        }
                    }">
                        <div class="post post-frame overlay-zoom">
                            <figure class="post-media">
                                <a href="post-single.html">
                                    <img src="images/demos/demo-beauty/blog/1.jpg" width="340" height="206"
                                         alt="post" />
                                </a>
                                <div class="post-calendar">
                                    <span class="post-day">03</span>
                                    <span class="post-month">DEC</span>
                                </div>
                            </figure>
                            <div class="post-details">
                                <h4 class="post-title"><a href="#">Sed adipiscing ornare</a></h4>
                                <p class="post-content">Sed pretium, ligula sollicitudin laoreet viverra, tortor libero
                                    sodales leo, eget blandit…</p>
                                <a href="blog-classic.html" class="btn btn-link btn-underline btn-primary">Read
                                    More<i class="d-icon-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="post post-frame overlay-zoom">
                            <figure class="post-media">
                                <a href="post-single.html">
                                    <img src="images/demos/demo-beauty/blog/2.jpg" width="340" height="206"
                                         alt="post" />
                                </a>
                                <div class="post-calendar">
                                    <span class="post-day">01</span>
                                    <span class="post-month">DEC</span>
                                </div>
                            </figure>
                            <div class="post-details">
                                <h4 class="post-title"><a href="#">Sed pretium sollicitudin leo</a></h4>
                                <p class="post-content">Sed pretium, ligula sollicitudin laoreet viverra, tortor libero
                                    sodales leo, eget blandit…</p>
                                <a href="blog-classic.html" class="btn btn-link btn-underline btn-primary">Read
                                    More<i class="d-icon-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="post post-frame overlay-zoom">
                            <figure class="post-media">
                                <a href="post-single.html">
                                    <img src="images/demos/demo-beauty/blog/3.jpg" width="340" height="206"
                                         alt="post" />
                                </a>
                                <div class="post-calendar">
                                    <span class="post-day">02</span>
                                    <span class="post-month">DEC</span>
                                </div>
                            </figure>
                            <div class="post-details">
                                <h4 class="post-title"><a href="#">Ornare risus utaliquam</a></h4>
                                <p class="post-content">Sed pretium, ligula sollicitudin laoreet viverra, tortor libero
                                    sodales leo, eget blandit…</p>
                                <a href="blog-classic.html" class="btn btn-link btn-underline btn-primary">Read
                                    More<i class="d-icon-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="brand-section container pt-10 pb-10 appear-animate" data-animation-options="{
                        'delay': '.3s'
                    }">
                    <div class="with-border mb-6 mt-6">
                        <div class="owl-carousel mt-4 mb-4 owl-theme row brand-carousel cols-xl-6 cols-lg-5 cols-md-4 cols-sm-3 cols-2"
                             data-owl-options="{
                                    'nav': false,
                                    'dots': false,
                                    'autoplay': true,
                                    'margin': 20,
                                    'loop': true,
                                    'responsive': {
                                        '0': {
                                            'items': 2
                                        },
                                        '576': {
                                            'items': 3
                                        },
                                        '768': {
                                            'items': 4
                                        },
                                        '992': {
                                            'items': 5
                                        },
                                        '1200': {
                                            'items': 6
                                        }
                                    }
                                }">
                            <figure><img src="images/brands/1.png" alt="brand" width="180" height="100" />
                            </figure>
                            <figure><img src="images/brands/2.png" alt="brand" width="180" height="100" />
                            </figure>
                            <figure><img src="images/brands/3.png" alt="brand" width="180" height="100" />
                            </figure>
                            <figure><img src="images/brands/4.png" alt="brand" width="180" height="100" />
                            </figure>
                            <figure><img src="images/brands/5.png" alt="brand" width="180" height="100" />
                            </figure>
                            <figure><img src="images/brands/6.png" alt="brand" width="180" height="100" />
                            </figure>
                        </div>
                    </div>
                </div>
                <section class="instagram-section pt-10 pb-10 appear-animate" data-animation-options="{
                        'delay': '.3s'
                    }">
                    <div class="container pb-8 pt-8">
                        <h2 class="title title-underline text-center">Instagram</h2>
                        <div class="owl-carousel owl-theme row brand-carousel cols-xl-5 cols-lg-4 cols-md-3 cols-sm-2 cols-2"
                             data-owl-options="{
                                        'nav': false,
                                        'autoplay': true,
                                        'margin': 20,
                                        'loop': false,
                                        'responsive': {
                                            '0': {
                                                'items': 2
                                            },
                                            '576': {
                                                'items': 3
                                            },
                                            '992': {
                                                'items': 4
                                            }
                                        }
                                    }">
                            <figure class="instagram">
                                <a href="#"><img src="images/demos/demo-beauty/instagram/1.jpg" alt="brand" width="280"
                                                 height="280" /></a>
                            </figure>
                            <figure class="instagram">
                                <a href="#"><img src="images/demos/demo-beauty/instagram/2.jpg" alt="brand" width="280"
                                                 height="280" /></a>
                            </figure>
                            <figure class="instagram">
                                <a href="#"><img src="images/demos/demo-beauty/instagram/3.jpg" alt="brand" width="280"
                                                 height="280" /></a>
                            </figure>
                            <figure class="instagram">
                                <a href="#"><img src="images/demos/demo-beauty/instagram/4.jpg" alt="brand" width="280"
                                                 height="280" /></a>
                            </figure>
                        </div>
                    </div>
                </section>
            </div>

        </main>
        <!-- End of Main -->

    </div>

    <!-- Sticky Footer -->
    <div class="sticky-footer sticky-content fix-bottom">
        <a href="demo-beauty.html" class="sticky-link">
            <i class="d-icon-home"></i>
            <span>Home</span>
        </a>
        <a href="demo-beauty-shop.html" class="sticky-link">
            <i class="d-icon-volume"></i>
            <span>Categories</span>
        </a>
        <a href="wishlist.html" class="sticky-link">
            <i class="d-icon-heart"></i>
            <span>Wishlist</span>
        </a>
        <a href="account.html" class="sticky-link">
            <i class="d-icon-user"></i>
            <span>Account</span>
        </a>
        <div class="header-search hs-toggle dir-up">
            <a href="#" class="search-toggle sticky-link">
                <i class="d-icon-search"></i>
                <span>Search</span>
            </a>
            <form action="#" class="input-wrapper">
                <input type="text" class="form-control" name="search" autocomplete="off"
                       placeholder="Search your keyword..." required />
                <button class="btn btn-search" type="submit" title="submit-button">
                    <i class="d-icon-search"></i>
                </button>
            </form>
        </div>
    </div>
    <!-- Scroll Top -->
    <a id="scroll-top" href="#top" title="Top" role="button" class="scroll-top"><i class="d-icon-arrow-up"></i></a>

    <!-- MobileMenu -->
    <div class="mobile-menu-wrapper">
        <div class="mobile-menu-overlay">
        </div>
        <!-- End of Overlay -->
        <a class="mobile-menu-close" href="#"><i class="d-icon-times"></i></a>
        <!-- End of CloseButton -->
        <div class="mobile-menu-container scrollable">
            <form action="#" class="input-wrapper">
                <input type="text" class="form-control" name="search" autocomplete="off"
                       placeholder="Search your keyword..." required />
                <button class="btn btn-search" type="submit" title="submit-button">
                    <i class="d-icon-search"></i>
                </button>
            </form>
            <!-- End of Search Form -->
            <ul class="mobile-menu mmenu-anim">
                <li>
                    <a href="demo-beauty.html">Home</a>
                </li>
                <li>
                    <a href="demo-beauty-shop.html">Categories</a>
                    <ul>
                        <li>
                            <a href="#">
                                Variations 1
                            </a>
                            <ul>
                                <li><a href="shop-classic-filter.html">Classic Filter</a></li>
                                <li><a href="shop-left-toggle-sidebar.html">Left Toggle Filter</a></li>
                                <li><a href="shop-right-toggle-sidebar.html">Right Toggle Sidebar</a></li>
                                <li><a href="shop-horizontal-filter.html">Horizontal Filter </a>
                                </li>
                                <li><a href="shop-navigation-filter.html">Navigation Filter</a></li>

                                <li><a href="shop-off-canvas-filter.html">Off-Canvas Filter </a></li>
                                <li><a href="shop-top-banner.html">Top Banner</a></li>
                                <li><a href="shop-inner-top-banner.html">Inner Top Banner</a></li>
                                <li><a href="shop-with-bottom-block.html">With Bottom Block</a></li>
                                <li><a href="shop-category-in-page-header.html">Category In Page Header</a>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                Variations 2
                            </a>
                            <ul>
                                <li><a href="shop-grid-3cols.html">3 Columns Mode</a></li>
                                <li><a href="shop-grid-4cols.html">4 Columns Mode</a></li>
                                <li><a href="shop-grid-5cols.html">5 Columns Mode</a></li>
                                <li><a href="shop-grid-6cols.html">6 Columns Mode</a></li>
                                <li><a href="shop-grid-7cols.html">7 Columns Mode</a></li>
                                <li><a href="shop-grid-8cols.html">8 Columns Mode</a></li>
                                <li><a href="shop-list-mode.html">List Mode</a></li>
                                <li><a href="shop-pagination.html">Pagination</a></li>
                                <li><a href="shop-infinite-ajaxscroll.html">Infinite Ajaxscroll </a></li>
                                <li><a href="shop-loadmore-button.html">Loadmore Button</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                Variations 3
                            </a>
                            <ul>
                                <li><a href="shop-category-grid-shop.html">Category Grid Shop</a></li>
                                <li><a href="shop-category+products.html">Category + Products</a></li>
                                <li><a href="shop-default-1.html">Shop Default 1 </a>
                                </li>
                                <li><a href="shop-default-2.html">Shop Default 2</a></li>
                                <li><a href="shop-default-3.html">Shop Default 3</a></li>
                                <li><a href="shop-default-4.html">Shop Default 4</a></li>
                                <li><a href="shop-default-5.html">Shop Default 5</a></li>
                                <li><a href="shop-default-6.html">Shop Default 6</a></li>
                                <li><a href="shop-default-7.html">Shop Default 7</a></li>
                                <li><a href="shop-default-8.html">Shop Default 8</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="demo-beauty-product.html">Products</a>
                    <ul>
                        <li>
                            <a href="#">Product Pages</a>
                            <ul>
                                <li><a href="product-simple.html">Simple Product</a></li>
                                <li><a href="product-featured.html">Featured &amp; On Sale</a></li>
                                <li><a href="product.html">Variable Product</a></li>
                                <li><a href="product-variable-swatch.html">Variation Swatch
                                        Product</a></li>
                                <li><a href="product-grouped.html">Grouped Product </a></li>
                                <li><a href="product-external.html">External Product</a></li>
                                <li><a href="product-in-stock.html">In Stock Product</a></li>
                                <li><a href="product-out-stock.html">Out of Stock Product</a></li>
                                <li><a href="product-upsell.html">Upsell Products</a></li>
                                <li><a href="product-cross-sell.html">Cross Sell Products</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Product Layouts</a>
                            <ul>
                                <li><a href="product-vertical.html">Vertical Thumb</a></li>
                                <li><a href="product-horizontal.html">Horizontal Thumb</a></li>
                                <li><a href="product-gallery.html">Gallery Type</a></li>
                                <li><a href="product-grid.html">Grid Images</a></li>
                                <li><a href="product-masonry.html">Masonry Images</a></li>
                                <li><a href="product-sticky.html">Sticky Info</a></li>
                                <li><a href="product-sticky-both.html">Left & Right Sticky</a></li>
                                <li><a href="product-left-sidebar.html">With Left Sidebar</a></li>
                                <li><a href="product-right-sidebar.html">With Right Sidebar</a></li>
                                <li><a href="product-full.html">Full Width Layout </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Product Features</a>
                            <ul>
                                <li><a href="product-sale.html">Sale Countdown</a></li>
                                <li><a href="product-hurryup.html">Hurry Up Notification </a></li>
                                <li><a href="product-attribute-guide.html">Attribute Guide </a></li>
                                <li><a href="product-sticky-cart.html">Add Cart Sticky</a></li>
                                <li><a href="product-thumbnail-label.html">Labels on Thumbnail</a>
                                </li>
                                <li><a href="product-more-description.html">More Description
                                        Tabs</a></li>
                                <li><a href="product-accordion-data.html">Data In Accordion</a></li>
                                <li><a href="product-tabinside.html">Data Inside</a></li>
                                <li><a href="product-video.html">Video Thumbnail </a>
                                </li>
                                <li><a href="product-360-degree.html">360 Degree Thumbnail </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Pages</a>
                    <ul>
                        <li><a href="about-us.html">About</a></li>
                        <li><a href="contact-us.html">Contact Us</a></li>
                        <li><a href="account.html">Login</a></li>
                        <li><a href="faq.html">FAQs</a></li>
                        <li><a href="error-404.html">Error 404</a>
                            <ul>
                                <li><a href="error-404.html">Error 404-1</a></li>
                                <li><a href="error-404-1.html">Error 404-2</a></li>
                                <li><a href="error-404-2.html">Error 404-3</a></li>
                                <li><a href="error-404-3.html">Error 404-4</a></li>
                            </ul>
                        </li>
                        <li><a href="coming-soon.html">Coming Soon</a></li>
                    </ul>
                </li>
                <li>
                    <a href="blog-classic.html">Blog</a>
                    <ul>
                        <li><a href="blog-classic.html">Classic</a></li>
                        <li><a href="blog-listing.html">Listing</a></li>
                        <li>
                            <a href="#">Grid</a>
                            <ul>
                                <li><a href="blog-grid-2col.html">Grid 2 columns</a></li>
                                <li><a href="blog-grid-3col.html">Grid 3 columns</a></li>
                                <li><a href="blog-grid-4col.html">Grid 4 columns</a></li>
                                <li><a href="blog-grid-sidebar.html">Grid sidebar</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Masonry</a>
                            <ul>
                                <li><a href="blog-masonry-2col.html">Masonry 2 columns</a></li>
                                <li><a href="blog-masonry-3col.html">Masonry 3 columns</a></li>
                                <li><a href="blog-masonry-4col.html">Masonry 4 columns</a></li>
                                <li><a href="blog-masonry-sidebar.html">Masonry sidebar</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Mask</a>
                            <ul>
                                <li><a href="blog-mask-grid.html">Blog mask grid</a></li>
                                <li><a href="blog-mask-masonry.html">Blog mask masonry</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="post-single.html">Single Post</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="element.html">Elements</a>
                    <ul>
                        <li>
                            <a href="#">Elements 1</a>
                            <ul>
                                <li><a href="element-accordions.html">Accordions</a></li>
                                <li><a href="element-alerts.html">Alert &amp; Notification</a></li>

                                <li><a href="element-banner-effect.html">Banner Effect

                                    </a></li>
                                <li><a href="element-banner.html">Banner
                                    </a></li>
                                <li><a href="element-blog-posts.html">Blog Posts</a></li>
                                <li><a href="element-breadcrumb.html">Breadcrumb
                                    </a></li>
                                <li><a href="element-buttons.html">Buttons</a></li>
                                <li><a href="element-cta.html">Call to Action</a></li>
                                <li><a href="element-countdown.html">Countdown
                                    </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Elements 2</a>
                            <ul>
                                <li><a href="element-counter.html">Counter </a></li>
                                <li><a href="element-creative-grid.html">Creative Grid

                                    </a></li>
                                <li><a href="element-animation.html">Entrance Effect

                                    </a></li>
                                <li><a href="element-floating.html">Floating

                                    </a></li>
                                <li><a href="element-hotspot.html">Hotspot

                                    </a></li>
                                <li><a href="element-icon-boxes.html">Icon Boxes</a></li>
                                <li><a href="element-icons.html">Icons</a></li>
                                <li><a href="element-image-box.html">Image box

                                    </a></li>
                                <li><a href="element-instagrams.html">Instagrams</a></li>

                            </ul>
                        </li>
                        <li>
                            <a href="#">Elements 3</a>
                            <ul>

                                <li><a href="element-categories.html">Product Category</a></li>
                                <li><a href="element-products.html">Products</a></li>
                                <li><a href="element-product-banner.html">Products + Banner

                                    </a></li>
                                <li><a href="element-product-grid.html">Products + Grid

                                    </a></li>
                                <li><a href="element-product-single.html">Product Single

                                    </a>
                                </li>
                                <li><a href="element-product-tab.html">Products + Tab

                                    </a></li>
                                <li><a href="element-single-product.html">Single Product

                                    </a></li>
                                <li><a href="element-slider.html">Slider

                                    </a></li>
                                <li><a href="element-social-link.html">Social Icons </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Elements 4</a>
                            <ul>
                                <li><a href="element-subcategory.html">Subcategory

                                    </a></li>
                                <li><a href="element-svg-floating.html">Svg Floating

                                    </a></li>
                                <li><a href="element-tabs.html">Tabs</a></li>
                                <li><a href="element-testimonials.html">Testimonials
                                    </a></li>
                                <li><a href="element-titles.html">Title</a></li>
                                <li><a href="element-typography.html">Typography</a></li>
                                <li><a href="element-vendor.html">Vendor

                                    </a></li>
                                <li><a href="element-video.html">Video

                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="https://d-themes.com/buynow/riodehtml" rel="noreferrer"> Buy Riode!</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- newsletter-popup type3 -->
    <div class="newsletter-popup newsletter-pop3 mfp-hide" id="newsletter-popup">
        <div class="newsletter-content">
            <h2 class="font-weight-bolder text-uppercase">Newsletter Sign up</h2>
            <p class="text-grey">Exclusive access to weekly drops, new arrivals & events.
                Get latest fashion updates & offers.</p>
            <form action="#" method="get" class="input-wrapper input-wrapper-inline input-wrapper-round">
                <input type="email" class="form-control email" name="email" id="email2"
                       placeholder="Email address here..." required="">
                <button class="btn btn-dark text-uppercase" type="submit">Sign me up</button>
            </form>
            <div class="d-flex form-check">
                <div class="form-checkbox justify-content-center">
                    <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup"
                           name="hide-newsletter-popup" required />
                    <label for="hide-newsletter-popup">Don't show this popup again</label>
                </div>
                <a href="#" class="form-privacy">
                    Privacy Policy
                </a>
            </div>
        </div>
    </div>



@endsection('content')
