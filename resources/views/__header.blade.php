<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left">
            </div>
            <div class="header-right">
                <!-- End DropDown Menu -->
                <span class="divider d-lg-show"></span>
                <a href="{{ route("contact") }}" class="contact d-lg-show ml-0"><i class="d-icon-map"></i>Contactez-nous</a>
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
                        <p class="text-white">+212 662-750030 </p>
                    </div>
                </a>
            </div>
            <div class="header-center">
                <a href="{{route('index')}}" class="logo">
                    <img src="{{asset('images/logo/logo.jpg')}}" alt="logo-muster-dickson" width="187" height="75" />
                </a>
                <!-- End Header Search -->
            </div>
            <div class="header-right">

                <div class="dropdown wishlist wishlist-dropdown off-canvas">
                    <div class="canvas-overlay"></div>
                    <div class="dropdown-box scrollable">
                        <div class="products scrollable">
                            <div class="product product-wishlist">
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
                            <span class="cart-price"></span>
                        </div>
                        <i class="d-icon-bag"><span class="cart-count">0</span></i>
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
                            <a href="{{route('index')}}">Home</a>
                        </li>
                        <li class="">
                            <a href="{{route('index')}}">Coiffeur</a>
                        </li>
                        <li class="">
                            <a href="{{route('video')}}">Video</a>
                        </li>
                        <li class="">
                            <a href="{{route('contact')}}">Contact</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- End Header -->
