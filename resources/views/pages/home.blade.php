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
{{--        <link rel="icon" type="image/png" href="images/icons/favicon.png">--}}
        <!-- Preload Font -->
        <link rel="preload" href="{{asset('fonts/front/riode.ttf')}}" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="{{asset('vendor/template/fontawesome-free/webfonts/fa-solid-900.woff2')}}" as="font" type="font/woff2"
              crossorigin="anonymous">
        <link rel="preload" href="{{asset('vendor/template/fontawesome-free/webfonts/fa-brands-400.woff2')}}" as="font" type="font/woff2"
              crossorigin="anonymous">

        <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/animate/animate.min.css')}}">

        <!-- Plugins CSS File -->
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/magnific-popup/magnific-popup.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/owl-carousel/owl.carousel.min.css')}}">

        <link rel="stylesheet" type="text/css" href="{{asset('vendor/template/sticky-icon/stickyicon.css')}}">


        <!-- Main CSS File -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/template/css/demo19.css')}}">
    </head>

    <body class="home">

        <div class="page-wrapper">
            <header class="header">
                <div class="header-middle sticky-header fix-top sticky-content">
                    <div class="container-fluid">
                        <div class="header-left">
                            <a href="#" class="mobile-menu-toggle">
                                <i class="d-icon-bars2"></i>
                            </a>
                            <a href="index.html" class="logo">
                                <img src="images/logo/logo-no-bg.png" alt="logo" width="153" height="44" />
                            </a>
                            <!-- End Logo -->
                            <nav class="main-nav">
                                <ul class="menu">
                                    <li class="active">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="">
                                        <a href="index.html">Shop</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="header-center">
                        </div>
                        <div class="header-right">
                            <!-- End Divider -->
                            <div class="header-search hs-toggle mr-4">
                                <a href="#" class="search-toggle" title="search-toggle">
                                    <i class="d-icon-search"></i>
                                </a>
                                <form action="#" class="input-wrapper">
                                    <input type="text" class="form-control" name="search" autocomplete="off"
                                           placeholder="Search your keyword..." required />
                                    <button class="btn btn-search" type="submit" title="submit-button">
                                        <i class="d-icon-search"></i>
                                    </button>
                                </form>
                            </div>
                            <!-- End Header Search -->
                            <a href="ajax/login.html" class="login-link d-lg-show mr-4" title="login"><i
                                class="d-icon-user"></i></a>

                            <div class="dropdown wishlist wishlist-dropdown off-canvas mr-4 d-lg-show">
                                <a href="wishlist.html" class="wishlist-toggle" title="wishlist">
                                    <i class="d-icon-heart"></i>
                                </a>
                                <div class="canvas-overlay"></div>
                                <!-- End Wishlist Toggle -->
                                <div class="dropdown-box scrollable">
                                    <div class="canvas-header">
                                        <h4 class="canvas-title">wishlist</h4>
                                        <a href="#" class="btn btn-dark btn-link btn-icon-right btn-close">close<i
                                            class="d-icon-arrow-right"></i><span class="sr-only">wishlist</span></a>
                                    </div>
                                    <div class="products scrollable">
                                        <div class="product product-wishlist">
                                            <figure class="product-media">
                                                <a href="product.html">
                                                    <img src="images/wishlist/product-1.jpg" width="100" height="100"
                                                         alt="product" />
                                                </a>
                                                <button class="btn btn-link btn-close">
                                                    <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                                </button>
                                            </figure>
                                            <div class="product-detail">
                                                <a href="product.html" class="product-name">Girl's Dark Bag</a>
                                                <div class="price-box">
                                                    <span class="product-price">$84.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of wishlist Product -->
                                        <div class="product product-wishlist">
                                            <figure class="product-media">
                                                <a href="product.html">
                                                    <img src="images/wishlist/product-2.jpg" width="100" height="100"
                                                         alt="product" />
                                                </a>
                                                <button class="btn btn-link btn-close">
                                                    <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                                </button>
                                            </figure>
                                            <div class="product-detail">
                                                <a href="product.html" class="product-name">Women's Fashional Comforter
                                                </a>
                                                <div class="price-box">
                                                    <span class="product-price">$84.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of wishlist Product -->
                                        <div class="product product-wishlist">
                                            <figure class="product-media">
                                                <a href="product.html">
                                                    <img src="images/wishlist/product-3.jpg" width="100" height="100"
                                                         alt="product" />
                                                </a>
                                                <button class="btn btn-link btn-close">
                                                    <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                                </button>
                                            </figure>
                                            <div class="product-detail">
                                                <a href="product.html" class="product-name">Wide Knickerbockers</a>
                                                <div class="price-box">
                                                    <span class="product-price">$84.00</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- End of wishlist Product -->
                                    </div>
                                    <a href="wishlist.html" class="btn btn-dark wishlist-btn mt-4"><span>Go To
                                                Wishlist</span></a>
                                    <!-- End of Products  -->
                                </div>
                                <!-- End Dropdown Box -->
                            </div>

                            <div class="dropdown cart-dropdown type2 mr-0 mr-lg-2">
                                <a href="#" class="cart-toggle link">
                                    <i class="d-icon-bag"><span class="cart-count">2</span></i>
                                </a>
                                <div class="dropdown-box">
                                    <div class="products scrollable">
                                        <div class="product product-cart">
                                            <figure class="product-media">
                                                <a href="product.html">
                                                    <img src="images/product-img/p1.jpg" alt="product" width="80"
                                                         height="88" />
                                                </a>
                                                <button class="btn btn-link btn-close">
                                                    <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                                </button>
                                            </figure>
                                            <div class="product-detail">
                                                <a href="product.html" class="product-name">Riode White Trends</a>
                                                <div class="price-box">
                                                    <span class="product-quantity">1</span>
                                                    <span class="product-price">$21.00</span>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End of Cart Product -->
                                        <div class="product product-cart">
                                            <figure class="product-media">
                                                <a href="product.html">
                                                    <img src="images/product-img/p2.png" alt="product" width="80"
                                                         height="88" />
                                                </a>
                                                <button class="btn btn-link btn-close">
                                                    <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                                </button>
                                            </figure>
                                            <div class="product-detail">
                                                <a href="product.html" class="product-name">Dark Blue Women’s
                                                    Leomora Hat</a>
                                                <div class="price-box">
                                                    <span class="product-quantity">1</span>
                                                    <span class="product-price">$118.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of Cart Product -->
                                    </div>
                                    <!-- End of Products  -->
                                    <div class="cart-total">
                                        <label>Subtotal:</label>
                                        <span class="price">$139.00</span>
                                    </div>
                                    <!-- End of Cart Total -->
                                    <div class="cart-action">
                                        <a href="cart.html" class="btn btn-dark btn-link">View Cart</a>
                                        <a href="checkout.html" class="btn btn-dark"><span>Go To Checkout</span></a>
                                    </div>
                                    <!-- End of Cart Action -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </header>
            <!-- End Header -->
            <main class="main">
                <div class="page-content">
                    <section class="intro-section">

                        <div class="intro-banners gutter-sm row">
                            <div class="col-lg-6 col-sm-6 mb-2">

                                <div class="banner banner-1 banner-fixed content-middle overlay-dark appear-animate zoom fadeInLeftShorter appear-animation-visible" data-animation-options="{
                                            'name': 'fadeInLeftShorter',
                                            'delay': '.8s'
                                        }" style="animation-duration: 1.2s;">
                                    <a href="muster-shop.html">
                                        <figure>
                                            <img src="images/demo-images/photo1.jpg" alt="banner" width="380" height="207" style="background-color: rgb(233, 233, 233); height: 75vh; opacity: 0.7;">
                                        </figure>
                                    </a>
                                    <div class="banner-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                        <h2 class="banner-title font-weight-bold text-black" style="font-size: 3rem;">Muster</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 mb-2">
                                <div class="banner banner-1 banner-fixed content-middle overlay-dark appear-animate zoom fadeInLeftShorter appear-animation-visible" data-animation-options="{
                                            'name': 'fadeInLeftShorter',
                                            'delay': '.8s'
                                        }" style="animation-duration: 1.2s;">
                                    <a href="dikson-shop.html">
                                        <figure>
                                            <img src="images/demo-images/photo2.jpg" alt="banner" width="380" height="207" style="background-color: rgb(233, 233, 233); height: 75vh; opacity: 0.7;">
                                        </figure>
                                    </a>
                                    <div class="banner-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                        <h2 class="banner-title font-weight-bold text-black" style="font-size: 3rem;">Dikson</h2>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </section>
                    <section class="banner-group container-fluid mt-10 pt-6 pb-8 mb-10 appear-animate">
                        <div class="owl-carousel owl-theme row cols-lg-2 cols-1" data-owl-options="{
                                'items': 2,
                                'dots': false,
                                'autoplay': true,
                                'loop': false,
                                'margin': 10,
                                'responsive': {
                                    '0': {
                                        'items': 1
                                    },
                                    '992': {
                                        'items': 2
                                    }
                                }
                            }">
                            <div class="banner1 banner banner-fixed overlay-effect1">
                                <figure style=" background-color:#27262b">
                                    <img src="images/demos/demo19/banner/4.jpg" alt="banner" width="881" height="150" />
                                </figure>
                                <div class="banner-content y-50">
                                    <h4 class="banner-subtitle text-uppercase text-body mb-0">
                                        New Arrivals
                                    </h4>
                                    <h3 class="banner-title font-weight-bolder ls-m mb-0" data-animation-options="{
                                            'name': 'blurIn',
                                            'delay': '.4s'
                                        }">Season Training Shoes</h3>
                                    <hr class="bg-grey border-no">
                                    <h4 class="font-weight-normal mb-0 ls-normal">Only from <span
                                        class="text-primary font-weight-semi-bold">$78.99</span>
                                    </h4>
                                </div>
                            </div>
                            <div class="banner2 banner banner-fixed overlay-effect1">
                                <figure style="background-color: #231f1c">
                                    <img src="images/demos/demo19/banner/5.jpg" alt="banner" width="881" height="150" />
                                </figure>
                                <div class="banner-content y-50">
                                    <h4 class="banner-subtitle text-uppercase mb-0">
                                        Top Products
                                    </h4>
                                    <h3 class="banner-title font-weight-bolder ls-m mb-0 appear-animate text-white"
                                        data-animation-options="{
                                            'name': 'blurIn',
                                            'delay': '.4s'
                                        }">Suitable Women Wear</h3>
                                    <hr class="bg-grey border-no">
                                    <h4 class="font-weight-normal mb-0 ls-normal">Only from <span
                                        class="text-primary font-weight-semi-bold">$121.99</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="banner video-banner parallax" data-parallax-options="{'speed': 3, 'offset': -40}"
                             data-image-src="images/demos/demo19/video.jpg" style="background-color: #292e32">
                        <div class="banner-content appear-animate text-center pr-4 pl-4">
                            <h4 class="banner-subtitle ls-s font-secondary text-white text-uppercase appear-animate"
                                data-animation-options="{
                                    'name': 'fadeInDownShorter'
                                }">Brand Experience</h4>
                            <h3 class="banner-title text-white text-uppercase font-weight-bold mb-5 appear-animate"
                                data-animation-options="{
                                    'name': 'blurIn',
                                    'delay': '.4s',
                                    'duration': '1.4s'
                                }">Premium for the pros 2021</h3>
                            <a class="btn-play btn-iframe text-white appear-animate" data-animation-options="{
                                    'name': 'fadeIn',
                                    'delay': '.8s'
                                }" href="video/memory-of-a-woman.mp4">
                                <i class="d-icon-play-solid ml-0"></i>
                            </a>
                        </div>
                    </section>

                </div>

            </main>
            <footer class="footer appear-animate" data-animation-options="{ 'delay': '.2s' }">
                <div class="footer-middle">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <div class="widget widget-about">
                                    <a href="index.html" class="logo-footer mb-5">
                                        <img src="images/demos/demo19/logo-footer.png" alt="logo-footer" width="154"
                                             height="43" />
                                    </a>
                                    <div class="widget-body">
                                        <p class="ls-s">Avec sa nouvelle branche au Maroc, l'entreprise <br />
                                            met plus de 50 ans d'expertise au service des coiffeurs  <br />
                                            et esthéticiennes, offrant des services. de qualité et une gamme de produits haut de gamme</p>
                                        <a href="mailto:mail@riode.com">sales@muster-dikson.ma</a>
                                    </div>
                                </div>
                                <!-- End of Widget -->
                            </div>
                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <div class="widget">
                                    <h4 class="widget-title">Compte</h4>
                                    <ul class="widget-body">
                                        <li><a href="#">Conditions Générales</a></li>
                                        <li><a href="#">Politique de Confidentialité</a></li>
                                        <li><a href="#">Privacy Policy</a></li>
                                        <li><a href="#">Site Map</a></li>
                                    </ul>
                                </div>
                                <!-- End of Widget -->
                            </div>
                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <div class="widget">
                                    <h4 class="widget-title">
                                        Obtenir de l'aide</h4>
                                    <ul class="widget-body">
                                        <li><a href="#">Expédition & Livraison
                                        </a></li>
                                        <li><a href="#">Brand</a></li>
                                        <li><a href="#">Options de Paiement</a></li>
                                        <li><a href="contact-us.html">Nous Contacter</a></li>
                                    </ul>
                                </div>
                                <!-- End of Widget -->
                            </div>
                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <div class="widget">
                                    <h4 class="widget-title">À propos de nous</h4>
                                    <ul class="widget-body">
                                        <li><a href="about-us.html">À propos de nous</a></li>
                                    </ul>
                                </div>
                                <!-- End of Widget -->
                            </div>
                            <div class="col-xl-3 col-lg-8">
                                <div class="widget mb-4">
                                    <h4 class="widget-title">Subscribe to our newsletter</h4>
                                    <div class="widget-body widget-newsletter mt-1">
                                        <form action="#" class="input-wrapper input-wrapper-inline mb-5">
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Email address here..." required />
                                            <button class="btn btn-primary font-weight-bold" type="submit">subscribe <i
                                                class="d-icon-arrow-right"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="footer-info d-flex align-items-center justify-content-between">
                                    <figure class="payment">
                                        <img src="images/demos/demo4/payment.png" alt="payment" width="135" height="24" />
                                    </figure>
                                    <div class="social-links">
                                        <a href="#" title="social-link"
                                           class="social-link social-facebook fab fa-facebook-f"></a>
                                        <a href="#" title="social-link"
                                           class="social-link social-twitter fab fa-twitter"></a>
                                        <a href="#" title="social-link"
                                           class="social-link social-linkedin fab fa-linkedin-in"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of FooterMiddle -->
                <div class="footer-bottom d-block text-center">
                    <p class="copyright">Riode eCommerce &copy; 2021. All Rights Reserved</p>
                </div>
                <!-- End of FooterBottom -->
            </footer>
            <!-- End Footer -->
        </div>
        <!-- Sticky Footer -->
        <div class="sticky-footer sticky-content fix-bottom">
            <a href="index.html" class="sticky-link">
                <i class="d-icon-home"></i>
                <span>Home</span>
            </a>
            <a href="demo19-shop.html" class="sticky-link">
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
            <!-- End Overlay -->
            <a class="mobile-menu-close" href="#"><i class="d-icon-times"></i></a>
            <!-- End CloseButton -->
            <div class="mobile-menu-container scrollable">
                <form action="#" class="input-wrapper">
                    <input type="text" class="form-control" name="search" autocomplete="off"
                           placeholder="Search your keyword..." required />
                    <button class="btn btn-search" type="submit" title="submit-button">
                        <i class="d-icon-search"></i>
                    </button>
                </form>
                <!-- End Search Form -->
                <ul class="mobile-menu mmenu-anim">
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a href="demo19-shop.html">Categories</a>
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
                        <a href="demo19-product.html">Products</a>
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
                    <li><a href="https://d-themes.com/buynow/riodehtml">Buy Riode!</a></li>
                </ul>
                <!-- End MobileMenu -->
            </div>
        </div>

        <!-- newsletter-popup type2 -->
        <div class="newsletter-popup newsletter-pop2 mfp-hide" id="newsletter-popup">
            <figure>
                <img src="images/newsletter-popup2.jpg" width="500" height="264" alt="newsletter2" />
            </figure>
            <div class="newsletter-content">
                <h2 class="font-weight-bolder">Join Our Mailing List</h2>
                <p class="text-grey">Stay Informed!Monthly Tips and Discount.</p>
                <form action="#" method="get" class="input-wrapper input-wrapper-inline input-wrapper-round">
                    <input type="email" class="form-control email" name="email" id="email2"
                           placeholder="Email address here..." required="">
                    <button class="btn btn-dark" type="submit">SUBMIT</button>
                </form>
                <div class="form-checkbox justify-content-center">
                    <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup"
                           required />
                    <label for="hide-newsletter-popup">Don't show this popup again</label>
                </div>
                <div class="social-links">
                    <a href="#" title="social-link" class="social-link social-facebook fab fa-facebook-f"></a>
                    <a href="#" title="social-link" class="social-link social-twitter fab fa-twitter"></a>
                    <a href="#" title="social-link" class="social-link social-linkedin fab fa-linkedin-in"></a>
                </div>
            </div>
        </div>


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
