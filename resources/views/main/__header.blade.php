<header class="header">
    <div class="header-top">
        <div class="container">
{{--            <div class="header-left">--}}
{{--                <p class="welcome-msg">Welcome to Riode store message or remove it!</p>--}}
{{--            </div>--}}
            <div class="header-right">
                <!-- End DropDown Menu -->
                <span class="divider"></span>
                <a href="{{route('contact')}}" class="contact d-lg-show"><i class="d-icon-map"></i>Contact</a>
{{--                <a href="#" class="help d-lg-show"><i class="d-icon-info"></i> Need Help</a>--}}
{{--                <a class="login-link" href="ajax/login.html" data-toggle="login-modal"><i--}}
{{--                        class="d-icon-user"></i>Sign in</a>--}}
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
                <a href="{{route('index')}}" class="logo">
                    <img src="{{asset('images/logo/logo.jpg')}}" alt="logo-muster-dickson" width="197" height="85" />
                </a>
                <!-- End Logo -->

{{--                <div class="header-search hs-simple">--}}
{{--                    <form action="#" class="input-wrapper">--}}
{{--                        <input type="text" class="form-control" name="search" autocomplete="off"--}}
{{--                               placeholder="Search..." required />--}}
{{--                        <button class="btn btn-search" type="submit">--}}
{{--                            <i class="d-icon-search"></i>--}}
{{--                        </button>--}}
{{--                    </form>--}}
{{--                </div>--}}
                <!-- End Header Search -->
            </div>
            <div class="header-right">
                <a href="tel:#" class="icon-box icon-box-side">
                    <div class="icon-box-icon mr-0 mr-lg-2">
                        <i class="d-icon-phone"></i>
                    </div>
                    <div class="icon-box-content d-lg-show">
                        <h4 class="icon-box-title">Appelez-nous:</h4>
                        <p>+212 662-750030</p>
                    </div>
                </a>
                <span class="divider"></span>
                <div class="dropdown wishlist wishlist-dropdown off-canvas">
{{--                    <a href="wishlist.html" class="wishlist-toggle">--}}
{{--                        <i class="d-icon-heart"></i>--}}
{{--                    </a>--}}
                    <div class="canvas-overlay"></div>
                    <!-- End Wishlist Toggle -->
                    <div class="dropdown-box scrollable">
{{--                        <div class="canvas-header">--}}
{{--                            <h4 class="canvas-title">wishlist</h4>--}}
{{--                            <a href="#" class="btn btn-dark btn-link btn-icon-right btn-close">close<i--}}
{{--                                    class="d-icon-arrow-right"></i><span class="sr-only">wishlist</span></a>--}}
{{--                        </div>--}}
{{--                        <div class="products scrollable">--}}
{{--                            <div class="product product-wishlist">--}}
{{--                                <figure class="product-media">--}}
{{--                                    <a href="product.html">--}}
{{--                                        <img src="images/wishlist/product-1.jpg" width="100" height="100"--}}
{{--                                             alt="product" />--}}
{{--                                    </a>--}}
{{--                                    <button class="btn btn-link btn-close">--}}
{{--                                        <i class="fas fa-times"></i><span class="sr-only">Close</span>--}}
{{--                                    </button>--}}
{{--                                </figure>--}}
{{--                                <div class="product-detail">--}}
{{--                                    <a href="product.html" class="product-name">Girl's Dark Bag</a>--}}
{{--                                    <div class="price-box">--}}
{{--                                        <span class="product-price">$84.00</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- End of wishlist Product -->--}}
{{--                            <div class="product product-wishlist">--}}
{{--                                <figure class="product-media">--}}
{{--                                    <a href="product.html">--}}
{{--                                        <img src="images/wishlist/product-2.jpg" width="100" height="100"--}}
{{--                                             alt="product" />--}}
{{--                                    </a>--}}
{{--                                    <button class="btn btn-link btn-close">--}}
{{--                                        <i class="fas fa-times"></i><span class="sr-only">Close</span>--}}
{{--                                    </button>--}}
{{--                                </figure>--}}
{{--                                <div class="product-detail">--}}
{{--                                    <a href="product.html" class="product-name">Women's Fashional Comforter--}}
{{--                                    </a>--}}
{{--                                    <div class="price-box">--}}
{{--                                        <span class="product-price">$84.00</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- End of wishlist Product -->--}}
{{--                            <div class="product product-wishlist">--}}
{{--                                <figure class="product-media">--}}
{{--                                    <a href="product.html">--}}
{{--                                        <img src="images/wishlist/product-3.jpg" width="100" height="100"--}}
{{--                                             alt="product" />--}}
{{--                                    </a>--}}
{{--                                    <button class="btn btn-link btn-close">--}}
{{--                                        <i class="fas fa-times"></i><span class="sr-only">Close</span>--}}
{{--                                    </button>--}}
{{--                                </figure>--}}
{{--                                <div class="product-detail">--}}
{{--                                    <a href="product.html" class="product-name">Wide Knickerbockers</a>--}}
{{--                                    <div class="price-box">--}}
{{--                                        <span class="product-price">$84.00</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- End of wishlist Product -->--}}
{{--                        </div>--}}
{{--                        <a href="wishlist.html" class="btn btn-dark wishlist-btn mt-4"><span>Go To--}}
{{--                                        Wishlist</span></a>--}}
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
                        <i class="d-icon-bag"><span class="cart-count">0</span></i>
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
                            <a href="{{route('cart.show')}}" class="btn btn-dark btn-link">View Cart</a>
                            <a href="{{route('checkout')}}" class="btn btn-dark"><span>Go To Checkout</span></a>
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
                            <a href="{{route('index')}}">Home</a>
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

                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
