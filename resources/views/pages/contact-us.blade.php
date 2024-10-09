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
                    <p class="welcome-msg">Welcome to Riode store message or remove it!</p>
                </div>
                <div class="header-right">
                    <div class="dropdown">
                        <a href="#currency">USD</a>
                        <ul class="dropdown-box">
                            <li><a href="#USD">USD</a></li>
                            <li><a href="#EUR">EUR</a></li>
                        </ul>
                    </div>
                    <!-- End DropDown Menu -->
                    <div class="dropdown ml-5">
                        <a href="#language">ENG</a>
                        <ul class="dropdown-box">
                            <li>
                                <a href="#USD">ENG</a>
                            </li>
                            <li>
                                <a href="#EUR">FRH</a>
                            </li>
                        </ul>
                    </div>
                    <!-- End DropDown Menu -->
                    <span class="divider"></span>
                    <a href="contact-us.html" class="contact d-lg-show"><i class="d-icon-map"></i>Contact</a>
                    <a href="#" class="help d-lg-show"><i class="d-icon-info"></i> Need Help</a>
                    <a class="login-link" href="ajax/login.html" data-toggle="login-modal"><i
                            class="d-icon-user"></i>Sign in</a>
                    <span class="delimiter">/</span>
                    <a class="register-link ml-0" href="ajax/login.html" data-toggle="login-modal">Register</a>
                    <!-- End of Login -->
                </div>
            </div>
        </div>
        <!-- End HeaderTop -->
        <div class="header-middle sticky-header fix-top sticky-content">
            <div class="container">
                <div class="header-left">
                    <a href="#" class="mobile-menu-toggle">
                        <i class="d-icon-bars2"></i>
                    </a>
                    <a href="demo1.html" class="logo">
                        <img src="images/logo/logo-no-bg.png" alt="logo" width="153" height="44" />
                    </a>
                    <!-- End Logo -->

                    <div class="header-search hs-simple">
                        <form action="#" class="input-wrapper">
                            <input type="text" class="form-control" name="search" autocomplete="off"
                                   placeholder="Search..." required />
                            <button class="btn btn-search" type="submit">
                                <i class="d-icon-search"></i>
                            </button>
                        </form>
                    </div>
                    <!-- End Header Search -->
                </div>
                <div class="header-right">
                    <a href="tel:#" class="icon-box icon-box-side">
                        <div class="icon-box-icon mr-0 mr-lg-2">
                            <i class="d-icon-phone"></i>
                        </div>
                        <div class="icon-box-content d-lg-show">
                            <h4 class="icon-box-title">Call Us Now:</h4>
                            <p>0(800) 123-456</p>
                        </div>
                    </a>
                    <span class="divider"></span>
                    <div class="dropdown wishlist wishlist-dropdown off-canvas">
                        <a href="wishlist.html" class="wishlist-toggle">
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
                    <span class="divider"></span>
                    <div class="dropdown cart-dropdown type2 off-canvas mr-0 mr-lg-2">
                        <a href="#" class="cart-toggle label-block link">
                            <div class="cart-label d-lg-show">
                                <span class="cart-name">Shopping Cart:</span>
                                <span class="cart-price">$0.00</span>
                            </div>
                            <i class="d-icon-bag"><span class="cart-count">2</span></i>
                        </a>
                        <div class="canvas-overlay"></div>
                        <!-- End Cart Toggle -->
                        <div class="dropdown-box">
                            <div class="canvas-header">
                                <h4 class="canvas-title">Shopping Cart</h4>
                                <a href="#" class="btn btn-dark btn-link btn-icon-right btn-close">close<i
                                        class="d-icon-arrow-right"></i><span class="sr-only">Cart</span></a>
                            </div>
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
                        <!-- End Dropdown Box -->
                    </div>
                </div>
            </div>
        </div>

        <div class="header-bottom d-lg-show">
            <div class="container">
                <div class="header-left">
                    <nav class="main-nav">
                        <ul class="menu">
                            <li>
                                <a href="demo1.html">Home</a>
                            </li>
                            <li>
                                <a href="shop.html">Categories</a>
                                <div class="megamenu">
                                    <div class="row">
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                            <h4 class="menu-title">Variations 1</h4>
                                            <ul>
                                                <li><a href="shop-classic-filter.html">Classic Filter</a></li>
                                                <li><a href="shop-left-toggle-sidebar.html">Left Toggle Filter</a>
                                                </li>
                                                <li><a href="shop-right-toggle-sidebar.html">Right Toggle
                                                        Sidebar</a></li>
                                                <li><a href="shop-horizontal-filter.html">Horizontal Filter </a>
                                                </li>
                                                <li><a href="shop-navigation-filter.html">Navigation Filter</a></li>

                                                <li><a href="shop-off-canvas-filter.html">Off-Canvas Filter </a>
                                                </li>
                                                <li><a href="shop-top-banner.html">Top Banner</a></li>
                                                <li><a href="shop-inner-top-banner.html">Inner Top Banner</a></li>
                                                <li><a href="shop-with-bottom-block.html">With Bottom Block</a></li>
                                                <li><a href="shop-category-in-page-header.html">Category In Page
                                                        Header</a>
                                            </ul>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                            <h4 class="menu-title">Variations 2</h4>
                                            <ul>
                                                <li><a href="shop-grid-3cols.html">3 Columns Mode</a></li>
                                                <li><a href="shop-grid-4cols.html">4 Columns Mode</a></li>
                                                <li><a href="shop-grid-5cols.html">5 Columns Mode</a></li>
                                                <li><a href="shop-grid-6cols.html">6 Columns Mode</a></li>
                                                <li><a href="shop-grid-7cols.html">7 Columns Mode</a></li>
                                                <li><a href="shop-grid-8cols.html">8 Columns Mode</a></li>
                                                <li><a href="shop-list-mode.html">List Mode</a></li>
                                                <li><a href="shop-pagination.html">Pagination</a></li>
                                                <li><a href="shop-infinite-ajaxscroll.html">Infinite Ajaxscroll </a>
                                                </li>
                                                <li><a href="shop-loadmore-button.html">Loadmore Button</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                            <h4 class="menu-title">Variations 3</h4>
                                            <ul>
                                                <li><a href="shop-category-grid-shop.html">Category Grid Shop</a>
                                                </li>
                                                <li><a href="shop-category+products.html">Category + Products</a>
                                                </li>
                                                <li><a href="shop-default-1.html">Shop Default 1 </a></li>
                                                <li><a href="shop-default-2.html">Shop Default 2</a>
                                                </li>
                                                <li><a href="shop-default-3.html">Shop Default 3</a>
                                                </li>
                                                <li><a href="shop-default-4.html">Shop Default 4</a>
                                                </li>
                                                <li><a href="shop-default-5.html">Shop Default 5</a>
                                                </li>
                                                <li><a href="shop-default-6.html">Shop Default 6</a>
                                                </li>
                                                <li><a href="shop-default-7.html">Shop Default 7</a>
                                                </li>
                                                <li><a href="shop-default-8.html">Shop Default 8</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div
                                            class="col-6 col-sm-4 col-md-4 col-lg-3 menu-banner menu-banner1 banner banner-fixed">
                                            <figure>
                                                <img src="images/menu/banner-1.jpg" alt="Menu banner" width="221"
                                                     height="330" />
                                            </figure>
                                            <div class="banner-content y-50">
                                                <h4 class="banner-subtitle font-weight-bold text-primary ls-m">Sale.
                                                </h4>
                                                <h3 class="banner-title font-weight-bold"><span
                                                        class="text-uppercase">Up to</span>70% Off</h3>
                                                <a href="shop.html" class="btn btn-link btn-underline">shop now<i
                                                        class="d-icon-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <!-- End Megamenu -->
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="product.html">Products</a>
                                <div class="megamenu">
                                    <div class="row">
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <h4 class="menu-title">Product Pages</h4>
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
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <h4 class="menu-title">Product Layouts</h4>
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
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <h4 class="menu-title">Product Features</h4>
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
                                                <li><a href="product-video.html">Video Thumbnail </a></li>
                                                <li><a href="product-360-degree.html">360 Degree Thumbnail </a></li>
                                            </ul>
                                        </div>
                                        <div
                                            class="col-6 col-sm-4 col-md-3 menu-banner menu-banner2 banner banner-fixed">
                                            <figure>
                                                <img src="images/menu/banner-2.jpg" alt="Menu banner" width="221"
                                                     height="330" />
                                            </figure>
                                            <div class="banner-content x-50 text-center">
                                                <h3 class="banner-title text-white text-uppercase">Sunglasses</h3>
                                                <h4 class="banner-subtitle font-weight-bold text-white mb-0">$23.00
                                                    -
                                                    $120.00</h4>
                                            </div>
                                        </div>
                                        <!-- End MegaMenu -->
                                    </div>
                                </div>
                            </li>
                            <li class="active">
                                <a href="#">Pages</a>
                                <ul>
                                    <li><a href="about-us.html">About</a></li>
                                    <li><a href="contact-us.html">Contact Us</a></li>
                                    <li><a href="account.html">My Account</a></li>
                                    <li><a href="wishlist.html">Wishlist</a></li>
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
                            <li class="d-xl-show">
                                <a href="element.html">Elements</a>
                                <div class="megamenu">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h4 class="menu-title">Elements 1</h4>
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
                                        </div>
                                        <div class="col-md-3">
                                            <h4 class="menu-title">Elements 2</h4>
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
                                        </div>
                                        <div class="col-md-3">
                                            <h4 class="menu-title">Elements 3</h4>
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
                                        </div>
                                        <div class="col-md-3">
                                            <h4 class="menu-title">Elements 4</h4>
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
                                        </div>
                                    </div>

                                </div>
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
                                <a href="about-us.html">About Us</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="header-right">
                    <a href="#"><i class="d-icon-card"></i>Special Offers</a>
                    <a href="https://d-themes.com/buynow/riodehtml" class="ml-6">Buy Riode!</a>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header -->
    <main class="main">
        <nav class="breadcrumb-nav">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="demo1.html"><i class="d-icon-home"></i></a></li>
                    <li>Contact Us</li>
                </ul>
            </div>
        </nav>
        <div class="page-header" style="background-image: url(images/page-header/contact-us.jpg)">
            <h1 class="page-title font-weight-bold text-capitalize ls-l">Contact Us</h1>
        </div>
        <div class="page-content mt-10 pt-7">
            <section class="contact-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 ls-m mb-4">
                            <div class="grey-section d-flex align-items-center h-100">
                                <div>
                                    <h4 class="mb-2 text-capitalize">Headquarters</h4>
                                    <p>1600 Amphitheatre Parkway<br>New York WC1 1BA</p>

                                    <h4 class="mb-2 text-capitalize">Phone Number</h4>
                                    <p>
                                        <a href="tel:#">1.800.458.56</a><br>
                                        <a href="tel:#">1.800.458.56</a>
                                    </p>

                                    <h4 class="mb-2 text-capitalize">Support</h4>
                                    <p class="mb-4">
                                        <a href="#">support@your-domain.com</a><br>
                                        <a href="#">help@your-domain.com</a><br>
                                        <a href="#">Sale</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 d-flex align-items-center mb-4">
                            <div class="w-100">
                                <form class="pl-lg-2" action="#">
                                    <h4 class="ls-m font-weight-bold">Let’s Connect</h4>
                                    <p>Your email address will not be published. Required fields are
                                        marked *</p>
                                    <div class="row mb-2">
                                        <div class="col-12 mb-4">
                                                <textarea class="form-control" required
                                                          placeholder="Comment*"></textarea>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <input class="form-control" type="text" placeholder="Name *" required>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <input class="form-control" type="email" placeholder="Email *" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark btn-rounded">Post Comment<i
                                            class="d-icon-arrow-right"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End About Section-->

            <section class="store-section mt-6 pt-10 pb-8">
                <div class="container">
                    <h2 class="title title-center mb-7 text-normal">Our store</h2>
                    <div class="row cols-sm-2 cols-lg-4">
                        <div class="store">
                            <figure class="banner-radius">
                                <img src="images/subpages/store-1.jpg" alt="store" width="280" height="280">
                                <h4 class="overlay-visible">New York</h4>
                                <div class="overlay overlay-transparent">
                                    <a class="mt-8" href="mail:#">mail@newyorkriodestore.com</a>
                                    <a href="tel:#">Phone: (123) 456-7890</a>
                                    <div class="social-links mt-1">
                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>
                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>
                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>
                                    </div>
                                </div>
                            </figure>
                        </div>
                        <div class="store">
                            <figure class="banner-radius">
                                <img src="images/subpages/store-2.jpg" alt="store" width="280" height="280">
                                <h4 class="overlay-visible">London</h4>
                                <div class="overlay overlay-transparent">
                                    <a class="mt-8" href="mail:#">mail@londonriodestore.com</a>
                                    <a href="tel:#">Phone: (123) 456-7890</a>
                                    <div class="social-links mt-1">
                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>
                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>
                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>
                                    </div>
                                </div>
                            </figure>
                        </div>
                        <div class="store">
                            <figure class="banner-radius">
                                <img src="images/subpages/store-3.jpg" alt="store" width="280" height="280">
                                <h4 class="overlay-visible">Oslo</h4>
                                <div class="overlay overlay-transparent">
                                    <a class="mt-8" href="mail:#">mail@osloriodestore.com</a>
                                    <a href="tel:#">Phone: (123) 456-7890</a>
                                    <div class="social-links mt-1">
                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>
                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>
                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>
                                    </div>
                                </div>
                            </figure>
                        </div>
                        <div class="store">
                            <figure class="banner-radius">
                                <img src="images/subpages/store-4.jpg" alt="store" width="280" height="280">
                                <h4 class="overlay-visible">Stockholm</h4>
                                <div class="overlay overlay-transparent">
                                    <a class="mt-8" href="mail:#">mail@stockholmriodestore.com</a>
                                    <a href="tel:#">Phone: (123) 456-7890</a>
                                    <div class="social-links mt-1">
                                        <a href="#" class="social-link social-facebook fab fa-facebook-f"></a>
                                        <a href="#" class="social-link social-twitter fab fa-twitter"></a>
                                        <a href="#" class="social-link social-linkedin fab fa-linkedin-in"></a>
                                    </div>
                                </div>
                            </figure>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Store Section -->

            <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
            <div class="grey-section google-map" id="googlemaps" style="height: 386px"></div>
            <!-- End Map Section -->
        </div>

    </main>
    <!-- End Main -->
    @include('__footer')

    <!-- End Footer -->
</div>
<!-- Sticky Footer -->
<div class="sticky-footer sticky-content fix-bottom">
    <a href="demo1.html" class="sticky-link">
        <i class="d-icon-home"></i>
        <span>Home</span>
    </a>
    <a href="shop.html" class="sticky-link">
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
            <button class="btn btn-search" type="submit">
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
            <button class="btn btn-search" type="submit">
                <i class="d-icon-search"></i>
            </button>
        </form>
        <!-- End Search Form -->
        <ul class="mobile-menu mmenu-anim">
            <li>
                <a href="demo1.html">Home</a>
            </li>
            <li>
                <a href="shop.html" class="active">Categories</a>
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
                <a href="product.html">Products</a>
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
        </ul>
        <!-- End MobileMenu -->
    </div>
</div>

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
</body>

</html>
