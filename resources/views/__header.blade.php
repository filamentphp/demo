<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left">
            </div>
            <div class="header-right">
                <!-- End DropDown Menu -->
                <span class="divider d-lg-show"></span>
                <a href="{{ route("contact") }}" class="contact d-lg-show ml-0"><i class="d-icon-map"></i>Contactez-nous</a>
                <span class="divider d-lg-show"></span>
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
{{--                        <li class="">--}}
{{--                            <a href="{{route('index')}}">Coiffeur</a>--}}
{{--                        </li>--}}
{{--                        <li class="">--}}
{{--                            <a href="{{route('video')}}">Video</a>--}}
                        </li>
                        <li class="">
                            <a href="{{route('contact')}}">Contact</a>
                        </li>
                        <li class="">
                            <a style="text-transform: none!important;" href="{{route('about_us')}}">À propos de nous</a>
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
