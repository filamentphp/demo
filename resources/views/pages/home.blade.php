@extends('.__base')

@section('content')

    @section('content')
    <body class="home">

        <div class="page-wrapper">


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
                <i class="fa-regular fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="account.html" class="sticky-link">
                <i class="d-icon-user"></i>
                <span>Account</span>
            </a>
            <div class="header-search hs-toggle dir-up">
                <a href="#" class="search-toggle sticky-link">
                   <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Search</span>
                </a>
                <form action="#" class="input-wrapper">
                    <input type="text" class="form-control" name="search" autocomplete="off"
                           placeholder="Search your keyword..." required />
                    <button class="btn btn-search" type="submit" title="submit-button">
                       <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
        <!-- Scroll Top -->
        <a id="scroll-top" href="#top" title="Top" role="button" class="scroll-top"><i class="fa-solid fa-arrow-up"></i></a>

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
                       <i class="fa-solid fa-magnifying-glass"></i>
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


    @endsection
