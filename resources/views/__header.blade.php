<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left">
{{--                <p class="welcome-msg ls-normal">Welcome to Riode store message or remove it!</p>--}}
            </div>
            <div class="header-right">
{{--                <div class="dropdown">--}}
{{--                    <a href="#currency">USD</a>--}}
{{--                    <ul class="dropdown-box">--}}
{{--                        <li><a href="#USD">USD</a></li>--}}
{{--                        <li><a href="#EUR">EUR</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
                <!-- End DropDown Menu -->
{{--                <div class="dropdown">--}}
{{--                    <a href="#language">ENG</a>--}}
{{--                    <ul class="dropdown-box">--}}
{{--                        <li>--}}
{{--                            <a href="#USD">ENG</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#EUR">FRH</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
                <!-- End DropDown Menu -->
                <span class="divider d-lg-show"></span>
                <a href="{{ route("contact") }}" class="contact d-lg-show ml-0"><i class="d-icon-map"></i>Contactez-nous</a>
{{--                <a href="#" class="help d-lg-show"><i class="d-icon-info"></i> Need Help</a>--}}
            </div>
        </div>
    </div>
    <!-- End HeaderTop -->
    <div class="header-middle">
        <div class="container">
            <div class="header-left">
                <a href="#" class="mobile-menu-toggle">
                    <i class="d-icon-bars2"></i>
                </a>
                <a href="tel:#" class="call icon-box icon-box-side">
                    <div class="icon-box-icon text-white p-0">
                        <i class="d-icon-phone"></i>
                    </div>
                    <div class="icon-box-content d-lg-show">
                        <h4 class="icon-box-title text-white">Appelez-nous:</h4>
                        <p class="text-white">++212 662-750030 </p>
                    </div>
                </a>
            </div>
            <div class="header-center">
                <a href="demo34.html" class="logo">
                    <img src="{{asset('images/logo/logo-muster-dickson.jpg')}}" alt="logo-muster-dickson" width="187" height="75" />
                </a>
                <!-- End Header Search -->
            </div>
            <div class="header-right">
{{--                <a href="wishlist.html" class="login-link mr-4 mr-lg-6" title="wishlist">--}}
{{--                    <i class="d-icon-user"></i>--}}
{{--                </a>--}}
                <div class="dropdown wishlist wishlist-dropdown off-canvas">
{{--                    <a href="wishlist.html" class="wishlist-toggle" title="wishlist">--}}
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
                        <div class="products scrollable">
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
                            <!-- End of wishlist Product -->
                            <div class="product product-wishlist">
{{--                                <figure class="product-media">--}}
{{--                                    <a href="product.html">--}}
{{--                                        <img src="images/wishlist/product-2.jpg" width="100" height="100"--}}
{{--                                             alt="product" />--}}
{{--                                    </a>--}}
{{--                                    <button class="btn btn-link btn-close">--}}
{{--                                        <i class="fas fa-times"></i><span class="sr-only">Close</span>--}}
{{--                                    </button>--}}
{{--                                </figure>--}}
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
{{--                                        <img src="images/wishlist/product-3.jpg" width="100" height="100"--}}
{{--                                             alt="product" />--}}
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
                <span class="divider mr-3"></span>
                <div class="dropdown cart-dropdown type2 off-canvas">
                    <a href="#" class="cart-toggle label-block link">
                        <div class="cart-label d-lg-show">
                            <span class="cart-name">Mon Panier:</span>
                            <span class="cart-price">$42.00</span>
                        </div>
                        <i class="d-icon-bag"><span class="cart-count">2</span></i>
                    </a>
                    <div class="canvas-overlay"></div>
                    <!-- End Cart Toggle -->
                    <div class="dropdown-box">
                        <div class="canvas-header">
                            <h4 class="canvas-title">Mon Panier</h4>
                            <a href="#" class="btn btn-dark btn-link btn-icon-right btn-close">close<i
                                    class="d-icon-arrow-right"></i><span class="sr-only">Cart</span></a>
                        </div>
                        <div class="products scrollable">
                            <div class="product product-cart">
                                <figure class="product-media">
                                    <a href="demo34-product.html">
                                        <img src="images/product-img/p1.jpg" alt="product" width="80"
                                             height="88" />
                                    </a>
                                    <button class="btn btn-link btn-close">
                                        <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                    </button>
                                </figure>
                                <div class="product-detail">
                                    <a href="demo34-product.html" class="product-name">Riode White Trends</a>
                                    <div class="price-box">
                                        <span class="product-quantity">1</span>
                                        <span class="product-price">$21.00</span>
                                    </div>
                                </div>

                            </div>
                            <!-- End of Cart Product -->
                            <div class="product product-cart">
                                <figure class="product-media">
                                    <a href="demo34-product.html">
                                        <img src="images/product-img/p2.png" alt="product" width="80"
                                             height="88" />
                                    </a>
                                    <button class="btn btn-link btn-close">
                                        <i class="fas fa-times"></i><span class="sr-only">Close</span>
                                    </button>
                                </figure>
                                <div class="product-detail">
                                    <a href="demo34-product.html" class="product-name">Dark Blue Women’s
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

    <div class="header-bottom sticky-header fix-top sticky-content">
        <div class="container">
            <div class="header-center justify-content-center">
                <nav class="main-nav">
                    <ul class="menu">
                        <li class="active">
                            <a href="demo34.html">Home</a>
                        </li>
                        <li>
                            <a href="demo34-shop.html">Categories</a>
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
                                        <li><a href="shop-default-1.html">Shop Default 1 </a></li>
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
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- End Header -->
