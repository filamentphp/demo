@extends('.__base')

@section('content')


    <div class="page-wrapper">
        <main class="main pt-6 with-border single-product">
            <div class="page-content mb-10 pb-6">
                <div class="container">
                    <div class="product product-single row">
                        <div class="col-md-6">
                            <div class="product-gallery product-gallery-sticky mb-lg-9 mb-4">
                                <div class="product-single-carousel owl-carousel owl-theme owl-nav-inner row cols-1">

                                    @foreach($product->getMedia('product-images') as $media)
                                        @if ($media->getUrl())
                                            <figure class="product-image" style="width: 450px; height: 550px; overflow: hidden;">
                                                <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                            </figure>
                                        @endif
                                    @endforeach

                                </div>
                                <div class="product-thumbs-wrap">
                                    <div class="product-thumbs">
                                        @foreach($product->getMedia('product-images') as $media)
                                            @if ($media->getUrl())
                                                <div class="product-thumb {{ $loop->first ? 'active' : '' }}">
                                                    <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}" width="109" height="122">
                                                </div>
                                            @else
                                                <img src="https://i.makeup.fr/i/i4/i4dfmpe8rxkj.png" alt="Product Image">
                                            @endif
                                        @endforeach
                                    </div>
                                    <button class="thumb-up disabled"><i class="fas fa-chevron-left"></i></button>
                                    <button class="thumb-down disabled"><i class="fas fa-chevron-right"></i></button>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="product-details">
                                <div class="product-navigation">
                                    <ul class="breadcrumb breadcrumb-lg">
                                        <li><a href="demo1.html"><i class="d-icon-home"></i></a></li>
                                        <li class="delimiter">/</li>
                                        <li><a href="product.html" class="active">Products</a></li>
                                        <li class="delimiter">/</li>
                                        <li>{{ $product->name }}</li>
                                    </ul>

                                    <ul class="product-nav">
                                        <li class="product-nav-prev">
                                            <a href="#">
                                                <i class="d-icon-arrow-left"></i> Prev
                                                <span class="product-nav-popup">
                                {{--                    <img src="images/product/product-thumb-prev.jpg"--}}
                                {{--                         alt="product thumbnail" width="110" height="123">--}}
                                                    <span class="product-name">Sed egtas Dnte Comfort</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="product-nav-next">
                                            <a href="#">
                                                Next <i class="d-icon-arrow-right"></i>
                                                <span class="product-nav-popup">
{{--                                                    <img src="images/product/product-thumb-next.jpg"--}}
{{--                                                         alt="product thumbnail" width="110" height="123">--}}
                                                    <span class="product-name">Sed egtas Dnte Comfort</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <h1 class="product-name">{{ $product->name }}</h1>
                                <div class="product-meta">
                                    SKU: <span class="product-sku">{{ $product->sku }}</span>
                                    @if(isset($product->brand->name))
                                        BRAND: <span class="product-brand">  {{ $product->brand->name }}
                                    @endif
                                    </span>
                                </div>
                                <div class="product-price">{{ $product->price }} MAD</div>
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width:80%"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <span class="link-to-tab rating-reviews">( 6 avis )</span>
                                </div>
                                <p class="product-short-desc">{!! $product->description !!}</p>

                                <hr class="product-divider">

                                <div class="product-form product-qty">
                                    <label>QTY:</label>
                                    <div class="product-form-group">
                                        <div class="input-group">
                                            <button class="quantity-minus d-icon-minus"></button>
                                            <input class="quantity form-control" type="number" min="1" value="1" max="1000000" id="product-quantity">
                                            <button class="quantity-plus d-icon-plus"></button>
                                        </div>

                                        <!-- Add the data attribute to identify the product ID -->
                                        <button class="btn-product btn-cart" id="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                            <i class="d-icon-bag"></i>Add To Cart
                                        </button>
                                    </div>
                                </div>

                                <!-- Display messages -->
                                <div id="message" style="display: none; color: green; margin-top: 20px;"></div>

                                <!-- Include the necessary script at the bottom of the template -->
                                @section('scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            // Add to Cart Button
                                            const addToCartButton = document.getElementById('add-to-cart-btn');

                                            addToCartButton.addEventListener('click', function (e) {
                                                e.preventDefault();

                                                const productId = this.getAttribute('data-product-id'); // Get product ID from data attribute
                                                const quantity = document.getElementById('product-quantity').value; // Get the selected quantity

                                                // Prepare the data for AJAX request
                                                const cartData = {
                                                    product_id: productId,
                                                    quantity: quantity,
                                                    _token: '{{ csrf_token() }}'
                                                };

                                                // Send AJAX request to add product to cart
                                                fetch('{{ route("cart.add") }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': cartData._token
                                                    },
                                                    body: JSON.stringify(cartData)
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        console.log(data);
                                                        if (data.success === 'Item added to cart successfully!') {
                                                            // Call alertCartAdded function
                                                            // alert("product added");
                                                        }
                                                    })
                                                    .catch(error => console.error('Error:', error));
                                            });

                                        });

                                    </script>
                                @endsection



                                <hr class="product-divider mb-3">

                                <div class="product-footer">
                                    <div class="social-links mr-4">
                                        <a href="{{route('index')}}" class="social-link social-facebook fab fa-facebook-f"></a>
                                        <a href="{{route('index')}}" class="social-link social-twitter fab fa-twitter"></a>
                                        <a href="{{route('index')}}" class="social-link social-pinterest fab fa-pinterest-p"></a>
                                    </div>
                                    <span class="divider d-lg-show"></span>
                                    <div class="product-action">
{{--                                        <a href="#" class="btn-product btn-wishlist mr-6"><i--}}
{{--                                                class="d-icon-heart"></i>Add to--}}
{{--                                            wishlist</a>--}}
                                        <span class="divider d-lg-show"></span>
{{--                                        <a href="#" class="btn-product btn-compare"><i--}}
{{--                                                class="d-icon-compare"></i>Compare</a>--}}
                                    </div>
                                </div>

                                <div class="accordion accordion-simple mb-4">
                                    <div class="card border-no card-description">
                                        <div class="card-header">
                                            <a href="#collapse1-1" class="collapse">Description</a>
                                        </div>
                                        <div id="collapse1-1" class="card-body expanded">
                                            <div class="row mt-5">
                                                <div class="mb-4">
                                                    <h5 class="description-title mb-4 font-weight-semi-bold ls-m">
                                                        Features</h5>
                                                    <p class="mb-2">
                                                        Praesent id enim sit amet.Tdio vulputate eleifend in in tortor.
                                                        ellus massa. siti iMassa ristique sit amet condim vel, facilisis
                                                        quimequistiqutiqu amet condim Dilisis Facilisis quis sapien.
                                                        Praesent id
                                                        enim sit amet.
                                                    </p>
                                                    <ul class="mb-8">
                                                        <li>Praesent id enim sit amet.Tdio vulputate</li>
                                                        <li>Eleifend in in tortor. ellus massa.Dristique sitii</li>
                                                        <li>Massa ristique sit amet condim vel</li>
                                                        <li>Dilisis Facilisis quis sapien. Praesent id enim sit amet
                                                        </li>
                                                    </ul>
                                                    <h5 class="description-title mb-3 font-weight-semi-bold ls-m">
                                                        Specifications
                                                    </h5>
                                                    <table class="table">
                                                        <tbody>
                                                        <tr>
                                                            <th class="font-weight-semi-bold text-dark pl-0">
                                                                Material</th>
                                                            <td class="pl-4">Praesent id enim sit amet.Tdio</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="font-weight-semi-bold text-dark pl-0">Claimed
                                                                Size</th>
                                                            <td class="pl-4">Praesent id enim sit</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="font-weight-semi-bold text-dark pl-0">
                                                                Recommended Use
                                                            </th>
                                                            <td class="pl-4">Praesent id enim sit amet.Tdio
                                                                vulputate eleifend
                                                                in in tortor. ellus massa. siti</td>
                                                        </tr>
                                                        <tr>
                                                            <th
                                                                class="font-weight-semi-bold text-dark border-no pl-0">
                                                                Manufacturer</th>
                                                            <td class="border-no pl-4">Praesent id enim</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="">
                                                    <h5 class="description-title font-weight-semi-bold ls-m mb-5">Video
                                                        Description
                                                    </h5>
                                                    <figure class="p-relative d-inline-block mb-2">
{{--                                                        <img src="images/product/product.jpg" width="559" height="370"--}}
{{--                                                             alt="Product" />--}}
                                                        <a class="btn-play btn-iframe"
                                                           href="video/memory-of-a-woman.mp4">
                                                            <i class="d-icon-play-solid"></i>
                                                        </a>
                                                    </figure>
                                                    <div class="icon-box-wrap d-flex flex-wrap">
                                                        <div
                                                            class="icon-box icon-box-side icon-border pt-2 pb-2 mb-4 mr-10">
                                                            <div class="icon-box-icon">
                                                                <i class="d-icon-lock"></i>
                                                            </div>
                                                            <div class="icon-box-content">
                                                                <h4 class="icon-box-title lh-1 pt-1 ls-s text-normal">2
                                                                    year
                                                                    warranty</h4>
                                                                <p>Guarantee with no doubt</p>
                                                            </div>
                                                        </div>
                                                        <div class="divider d-xl-show mr-10"></div>
                                                        <div class="icon-box icon-box-side icon-border pt-2 pb-2 mb-4">
                                                            <div class="icon-box-icon">
                                                                <i class="d-icon-truck"></i>
                                                            </div>
                                                            <div class="icon-box-content">
                                                                <h4 class="icon-box-title lh-1 pt-1 ls-s text-normal">
                                                                    Free shipping
                                                                </h4>
                                                                <p>On orders over $50.00</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
{{--                                    <div class="card card-additional">--}}
{{--                                        <div class="card-header">--}}
{{--                                            <a href="#collapse1-2" class="expand">Additional information</a>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body collapsed" id="collapse1-2">--}}
{{--                                            <ul class="list-none">--}}
{{--                                                <li><label>Brands:</label>--}}
{{--                                                    <p>SkillStar, SLS</p>--}}
{{--                                                </li>--}}
{{--                                                <li><label>Color:</label>--}}
{{--                                                    <p>Blue, Brown</p>--}}
{{--                                                </li>--}}
{{--                                                <li><label>Size:</label>--}}
{{--                                                    <p>Large, Medium, Small</p>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="card card-reviews">--}}
{{--                                        <div class="card-header">--}}
{{--                                            <a href="#collapse1-4" class="expand">Reviews (2)</a>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body collapsed" id="collapse1-4">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-12 mb-6">--}}
{{--                                                    <div class="avg-rating-container">--}}
{{--                                                        <mark>5.0</mark>--}}
{{--                                                        <div class="avg-rating">--}}
{{--                                                            <span class="avg-rating-title">Average Rating</span>--}}
{{--                                                            <div class="ratings-container mb-0">--}}
{{--                                                                <div class="ratings-full">--}}
{{--                                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                                </div>--}}
{{--                                                                <span class="rating-reviews">(2 Reviews)</span>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="ratings-list mb-2">--}}
{{--                                                        <div class="ratings-item">--}}
{{--                                                            <div class="ratings-container mb-0">--}}
{{--                                                                <div class="ratings-full">--}}
{{--                                                                    <span class="ratings" style="width:100%"></span>--}}
{{--                                                                    <span class="tooltiptext tooltip-top"></span>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="rating-percent">--}}
{{--                                                                <span style="width:100%;"></span>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="progress-value">100%</div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="ratings-item">--}}
{{--                                                            <div class="ratings-container mb-0">--}}
{{--                                                                <div class="ratings-full">--}}
{{--                                                                    <span class="ratings" style="width:80%"></span>--}}
{{--                                                                    <span class="tooltiptext tooltip-top">4.00</span>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="rating-percent">--}}
{{--                                                                <span style="width:0%;"></span>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="progress-value">0%</div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="ratings-item">--}}
{{--                                                            <div class="ratings-container mb-0">--}}
{{--                                                                <div class="ratings-full">--}}
{{--                                                                    <span class="ratings" style="width:60%"></span>--}}
{{--                                                                    <span class="tooltiptext tooltip-top">4.00</span>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="rating-percent">--}}
{{--                                                                <span style="width:0%;"></span>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="progress-value">0%</div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="ratings-item">--}}
{{--                                                            <div class="ratings-container mb-0">--}}
{{--                                                                <div class="ratings-full">--}}
{{--                                                                    <span class="ratings" style="width:40%"></span>--}}
{{--                                                                    <span class="tooltiptext tooltip-top">4.00</span>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="rating-percent">--}}
{{--                                                                <span style="width:0%;"></span>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="progress-value">0%</div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="ratings-item">--}}
{{--                                                            <div class="ratings-container mb-0">--}}
{{--                                                                <div class="ratings-full">--}}
{{--                                                                    <span class="ratings" style="width:20%"></span>--}}
{{--                                                                    <span class="tooltiptext tooltip-top">4.00</span>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="rating-percent">--}}
{{--                                                                <span style="width:0%;"></span>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="progress-value">0%</div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <a class="btn btn-dark btn-rounded submit-review-toggle"--}}
{{--                                                       href="#">Submit--}}
{{--                                                        Review</a>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-12 comments pt-2 pb-10 border-no">--}}
{{--                                                    <nav class="toolbox">--}}
{{--                                                        <div class="toolbox-left">--}}
{{--                                                            <div class="toolbox-item">--}}
{{--                                                                <a href="#" class="btn btn-outline btn-rounded">All--}}
{{--                                                                    Reviews</a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="toolbox-item">--}}
{{--                                                                <a href="#" class="btn btn-outline btn-rounded">With--}}
{{--                                                                    Images</a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="toolbox-item">--}}
{{--                                                                <a href="#" class="btn btn-outline btn-rounded">With--}}
{{--                                                                    Videos</a>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="toolbox-right">--}}
{{--                                                            <div class="toolbox-item toolbox-sort select-box text-dark">--}}
{{--                                                                <label>Sort By :</label>--}}
{{--                                                                <select name="orderby" class="form-control">--}}
{{--                                                                    <option value="">Default Order</option>--}}
{{--                                                                    <option value="newest" selected="selected">Newest--}}
{{--                                                                        Reviews--}}
{{--                                                                    </option>--}}
{{--                                                                    <option value="oldest">Oldest Reviews</option>--}}
{{--                                                                    <option value="high_rate">Highest Rating</option>--}}
{{--                                                                    <option value="low_rate">Lowest Rating</option>--}}
{{--                                                                    <option value="most_likely">Most Likely</option>--}}
{{--                                                                    <option value="most_unlikely">Most Unlikely</option>--}}
{{--                                                                </select>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </nav>--}}
{{--                                                    <ul class="comments-list">--}}
{{--                                                        <li>--}}
{{--                                                            <div class="comment">--}}
{{--                                                                <figure class="comment-media">--}}
{{--                                                                    <a href="#">--}}
{{--                                                                        <img src="images/blog/comments/1.jpg"--}}
{{--                                                                             alt="avatar">--}}
{{--                                                                    </a>--}}
{{--                                                                </figure>--}}
{{--                                                                <div class="comment-body">--}}
{{--                                                                    <div class="comment-rating ratings-container">--}}
{{--                                                                        <div class="ratings-full">--}}
{{--                                                                            <span class="ratings"--}}
{{--                                                                                  style="width:100%"></span>--}}
{{--                                                                            <span--}}
{{--                                                                                class="tooltiptext tooltip-top"></span>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="comment-user">--}}
{{--                                                                        <span class="comment-date">by <span--}}
{{--                                                                                class="font-weight-semi-bold text-uppercase text-dark">John--}}
{{--                                                                                Doe</span> on--}}
{{--                                                                            <span--}}
{{--                                                                                class="font-weight-semi-bold text-dark">Nov--}}
{{--                                                                                22,--}}
{{--                                                                                2018</span></span>--}}
{{--                                                                    </div>--}}

{{--                                                                    <div class="comment-content mb-5">--}}
{{--                                                                        <p>Sed pretium, ligula sollicitudin laoreet--}}
{{--                                                                            viverra, tortor--}}
{{--                                                                            libero sodales leo,--}}
{{--                                                                            eget blandit nunc tortor eu nibh. Nullam--}}
{{--                                                                            mollis. Ut--}}
{{--                                                                            justo.--}}
{{--                                                                            Suspendisse potenti.--}}
{{--                                                                            Sed egestas, ante et vulputate volutpat,--}}
{{--                                                                            eros pede--}}
{{--                                                                            semper--}}
{{--                                                                            est, vitae luctus metus libero eu augue.</p>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="file-input-wrappers">--}}
{{--                                                                        <img class="btn-play btn-img pwsp"--}}
{{--                                                                             src="images/products/product1.jpg"--}}
{{--                                                                             width="280" height="315" alt="product" />--}}


{{--                                                                        <img class="btn-play btn-img pwsp"--}}
{{--                                                                             src="images/products/product3.jpg"--}}
{{--                                                                             width="280" height="315" alt="product" />--}}

{{--                                                                        <a class="btn-play btn-iframe"--}}
{{--                                                                           style="background-image: url(images/product/product.jpg);background-size: cover;"--}}
{{--                                                                           href="video/memory-of-a-woman.mp4">--}}
{{--                                                                            <i class="d-icon-play-solid"></i>--}}
{{--                                                                        </a>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="feeling mt-5">--}}
{{--                                                                        <button--}}
{{--                                                                            class="btn btn-link btn-icon-left btn-slide-up btn-infinite like mr-2">--}}
{{--                                                                            <i class="fa fa-thumbs-up"></i>--}}
{{--                                                                            Like (<span class="count">0</span>)--}}
{{--                                                                        </button>--}}
{{--                                                                        <button--}}
{{--                                                                            class="btn btn-link btn-icon-left btn-slide-down btn-infinite unlike">--}}
{{--                                                                            <i class="fa fa-thumbs-down"></i>--}}
{{--                                                                            Unlike (<span class="count">0</span>)--}}
{{--                                                                        </button>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </li>--}}
{{--                                                        <li>--}}
{{--                                                            <div class="comment">--}}
{{--                                                                <figure class="comment-media">--}}
{{--                                                                    <a href="#">--}}
{{--                                                                        <img src="images/blog/comments/2.jpg"--}}
{{--                                                                             alt="avatar">--}}
{{--                                                                    </a>--}}
{{--                                                                </figure>--}}

{{--                                                                <div class="comment-body">--}}
{{--                                                                    <div class="comment-rating ratings-container">--}}
{{--                                                                        <div class="ratings-full">--}}
{{--                                                                            <span class="ratings"--}}
{{--                                                                                  style="width:100%"></span>--}}
{{--                                                                            <span--}}
{{--                                                                                class="tooltiptext tooltip-top"></span>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="comment-user">--}}
{{--                                                                        <span class="comment-date">by <span--}}
{{--                                                                                class="font-weight-semi-bold text-uppercase text-dark">John--}}
{{--                                                                                Doe</span> on--}}
{{--                                                                            <span--}}
{{--                                                                                class="font-weight-semi-bold text-dark">Nov--}}
{{--                                                                                22,--}}
{{--                                                                                2018</span></span>--}}
{{--                                                                    </div>--}}

{{--                                                                    <div class="comment-content">--}}
{{--                                                                        <p>Sed pretium, ligula sollicitudin laoreet--}}
{{--                                                                            viverra, tortor--}}
{{--                                                                            libero sodales leo, eget blandit nunc tortor--}}
{{--                                                                            eu nibh.--}}
{{--                                                                            Nullam--}}
{{--                                                                            mollis.--}}
{{--                                                                            Ut justo. Suspendisse potenti. Sed egestas,--}}
{{--                                                                            ante et--}}
{{--                                                                            vulputate volutpat,--}}
{{--                                                                            eros pede semper est, vitae luctus metus--}}
{{--                                                                            libero eu--}}
{{--                                                                            augue.--}}
{{--                                                                            Morbi purus libero,--}}
{{--                                                                            faucibus adipiscing, commodo quis, avida id,--}}
{{--                                                                            est. Sed--}}
{{--                                                                            lectus. Praesent elementum--}}
{{--                                                                            hendrerit tortor. Sed semper lorem at felis.--}}
{{--                                                                            Vestibulum--}}
{{--                                                                            volutpat.</p>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="feeling mt-5">--}}
{{--                                                                        <button--}}
{{--                                                                            class="btn btn-link btn-icon-left btn-slide-up btn-infinite like mr-2">--}}
{{--                                                                            <i class="fa fa-thumbs-up"></i>--}}
{{--                                                                            Like (<span class="count">0</span>)--}}
{{--                                                                        </button>--}}
{{--                                                                        <button--}}
{{--                                                                            class="btn btn-link btn-icon-left btn-slide-down btn-infinite unlike">--}}
{{--                                                                            <i class="fa fa-thumbs-down"></i>--}}
{{--                                                                            Unlike (<span class="count">0</span>)--}}
{{--                                                                        </button>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}

{{--                                                            </div>--}}
{{--                                                        </li>--}}
{{--                                                    </ul>--}}
{{--                                                    <nav class="toolbox toolbox-pagination justify-content-end">--}}
{{--                                                        <ul class="pagination">--}}
{{--                                                            <li class="page-item disabled">--}}
{{--                                                                <a class="page-link page-link-prev" href="#"--}}
{{--                                                                   aria-label="Previous" tabindex="-1"--}}
{{--                                                                   aria-disabled="true">--}}
{{--                                                                    <i class="d-icon-arrow-left"></i>Prev--}}
{{--                                                                </a>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="page-item active" aria-current="page"><a--}}
{{--                                                                    class="page-link" href="#">1</a>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="page-item"><a class="page-link" href="#">2</a>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="page-item"><a class="page-link" href="#">3</a>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="page-item page-item-dots"><a class="page-link"--}}
{{--                                                                                                    href="#">6</a>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="page-item">--}}
{{--                                                                <a class="page-link page-link-next" href="#"--}}
{{--                                                                   aria-label="Next">--}}
{{--                                                                    Next<i class="d-icon-arrow-right"></i>--}}
{{--                                                                </a>--}}
{{--                                                            </li>--}}
{{--                                                        </ul>--}}
{{--                                                    </nav>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <!-- End Comments -->--}}
{{--                                            <div class="review-form-section">--}}
{{--                                                <div class="review-overlay"></div>--}}
{{--                                                <div class="review-form-wrapper">--}}
{{--                                                    <div class="title-wrapper text-left">--}}
{{--                                                        <h3 class="title title-simple text-left text-normal">Add a--}}
{{--                                                            Review</h3>--}}
{{--                                                        <p>Your email address will not be published. Required fields are--}}
{{--                                                            marked *--}}
{{--                                                        </p>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="rating-form">--}}
{{--                                                        <label for="rating" class="text-dark">Your rating * </label>--}}
{{--                                                        <span class="rating-stars selected">--}}
{{--                                                            <a class="star-1" href="#">1</a>--}}
{{--                                                            <a class="star-2" href="#">2</a>--}}
{{--                                                            <a class="star-3" href="#">3</a>--}}
{{--                                                            <a class="star-4 active" href="#">4</a>--}}
{{--                                                            <a class="star-5" href="#">5</a>--}}
{{--                                                        </span>--}}

{{--                                                        <select name="rating" id="rating" required=""--}}
{{--                                                                style="display: none;">--}}
{{--                                                            <option value="">Rate…</option>--}}
{{--                                                            <option value="5">Perfect</option>--}}
{{--                                                            <option value="4">Good</option>--}}
{{--                                                            <option value="3">Average</option>--}}
{{--                                                            <option value="2">Not that bad</option>--}}
{{--                                                            <option value="1">Very poor</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                    <form action="#">--}}
{{--                                                        <textarea id="reply-message" cols="30" rows="6"--}}
{{--                                                                  class="form-control mb-4" placeholder="Comment *"--}}
{{--                                                                  required></textarea>--}}

{{--                                                        <div class="review-medias">--}}
{{--                                                            <div class="file-input form-control image-input"--}}
{{--                                                                 style="background-image: url(images/product/placeholder.png);">--}}
{{--                                                                <div class="file-input-wrapper">--}}
{{--                                                                </div>--}}
{{--                                                                <label class="btn-action btn-upload"--}}
{{--                                                                       title="Upload Media">--}}
{{--                                                                    <input type="file" accept=".png, .jpg, .jpeg"--}}
{{--                                                                           name="riode_comment_medias_image_1">--}}
{{--                                                                </label>--}}
{{--                                                                <label class="btn-action btn-remove"--}}
{{--                                                                       title="Remove Media">--}}
{{--                                                                </label>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="file-input form-control image-input"--}}
{{--                                                                 style="background-image: url(images/product/placeholder.png);">--}}
{{--                                                                <div class="file-input-wrapper">--}}
{{--                                                                </div>--}}
{{--                                                                <label class=" btn-action btn-upload"--}}
{{--                                                                       title="Upload Media">--}}
{{--                                                                    <input type="file" accept=".png, .jpg, .jpeg"--}}
{{--                                                                           name="riode_comment_medias_image_2">--}}
{{--                                                                </label>--}}
{{--                                                                <label class="btn-action btn-remove"--}}
{{--                                                                       title="Remove Media">--}}
{{--                                                                </label>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="file-input form-control video-input"--}}
{{--                                                                 style="background-image: url(images/product/placeholder.png);">--}}
{{--                                                                <video class="file-input-wrapper" controls=""></video>--}}
{{--                                                                <label class="btn-action btn-upload"--}}
{{--                                                                       title="Upload Media">--}}
{{--                                                                    <input type="file" accept=".avi, .mp4"--}}
{{--                                                                           name="riode_comment_medias_video_1">--}}
{{--                                                                </label>--}}
{{--                                                                <label class="btn-action btn-remove"--}}
{{--                                                                       title="Remove Media">--}}
{{--                                                                </label>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <p>Upload images and videos. Maximum count: 3, size: 2MB</p>--}}
{{--                                                        <button type="submit"--}}
{{--                                                                class="btn btn-primary btn-rounded">Submit<i--}}
{{--                                                                class="d-icon-arrow-right"></i></button>--}}
{{--                                                    </form>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <!-- End Reply -->--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>
        <!-- End of Main -->
    </div>


@endsection('content')
