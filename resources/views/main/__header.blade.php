<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-right">
                <!-- End DropDown Menu -->
                <span class="divider"></span>
                <a href="{{route('contact')}}" class="contact d-lg-show"><i class="d-icon-map"></i>Contact</a>
                <span class="delimiter">/</span>
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
                        <!-- End of Products  -->
                    </div>
                    <!-- End Dropdown Box -->
                </div>
                <span class="divider"></span>
                <div class="dropdown cart-dropdown type2 off-canvas mr-0 mr-lg-2">
                    <a href="{{route('cart.show')}}" class="label-block link">
                        <div class="cart-label d-lg-show">
                            <span class="cart-name">Panier:</span>
                            <span class="cart-price">{{ number_format($cartTotal, 2) }} MAD</span>
                        </div>
                        <i class="d-icon-bag"><span class="cart-count">{{ $cartItems->count() }}</span></i>
                    </a>
                    <div class="canvas-overlay"></div>
                </div>

                <script>
                    document.querySelectorAll('.btn-close').forEach(button => {
                        button.addEventListener('click', function () {
                            const productId = this.getAttribute('data-id');

                            fetch(`/cart/remove/${productId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            }).then(response => response.json())
                                .then(data => {
                                    location.reload();
                                });
                        });
                    });

                </script>
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
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
