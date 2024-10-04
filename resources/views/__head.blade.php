

<header class="header">
    <div class="header-middle sticky-header fix-top sticky-content">
        <div class="container-fluid">
            <div class="header-left">
                <a href="#" class="mobile-menu-toggle">
                    <i class="d-icon-bars2"></i>
                </a>
                <a href="{{route("index")}}" class="logo">
                    <img src="{{asset('images/logo/logo-no-bg.png')}}" alt="logo" width="306" height="88" />
                </a>
                <!-- End Logo -->
                <nav class="main-nav">
                    <ul class="menu">
                        <li class="active">
                            <a href="{{route("index")}}">Home</a>
                        </li>
                        <li class="">
                            <a href="{{route("shop.muster")}}">Shop</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="header-center">
            </div>
            <div class="header-right">
                <!-- End Divider -->

                <!-- End Header Search -->
                <a href="ajax/login.html" class="login-link d-lg-show mr-4" title="login">
                    <i class="fa-solid fa-user"></i>
                </a>

                <div class="dropdown wishlist wishlist-dropdown off-canvas mr-4 d-lg-show">
                    <a href="#" class="wishlist-toggle" title="wishlist">
                        <i class="fa-regular fa-heart"></i>
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
                        <i class="fa-solid fa-bag-shopping"></i><span class="cart-count">2</span></i>
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
