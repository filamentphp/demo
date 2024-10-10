@extends('.__base')

@section('content')

    <div class="page-wrapper">

        <main class="main">
            <div class="page-header"
                 style="background-image: url('images/demos/demo-beauty/page-header.jpg'); background-color: #ECEDF1;">
                <h1 class="page-title font-weight-bold text-dark">Muster Shop</h1>
                <ul class="breadcrumb pb-0">
                    <li class="text-dark"><a href="demo-beauty.html" class="text-dark"><i class="d-icon-home"></i></a>
                    </li>
                    <li class="delimiter text-dark">/</li>
                    <li class="text-dark">Muster Shop</li>
                </ul>
            </div>
            <!-- End PageHeader -->
            <div class="page-content mb-10 pb-3">
                <div class="container">
                    <nav class="toolbox toolbox-horizontal sticky-toolbox sticky-content fix-top">
                        <aside class="sidebar sidebar-fixed shop-sidebar">
                            <div class="sidebar-overlay"></div>
                            <a class="sidebar-close" href="#"><i class="d-icon-times"></i></a>
{{--                            <div class="sidebar-content toolbox-left">--}}
{{--                                <div class="toolbox-item select-menu">--}}
{{--                                    <a class="select-menu-toggle" href="#">Select Price</a>--}}
{{--                                    <ul class="filter-items">--}}
{{--                                        <li><a href="#">$0.00 - $50.00</a></li>--}}
{{--                                        <li><a href="#">$50.00 - $100.00</a></li>--}}
{{--                                        <li><a href="#">$100.00 - $200.00</a></li>--}}
{{--                                        <li><a href="#">$200.00+</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="toolbox-item select-menu">--}}
{{--                                    <a class="select-menu-toggle" href="#">Select Color</a>--}}
{{--                                    <ul class="filter-items">--}}
{{--                                        <li><a href="#">Black</a></li>--}}
{{--                                        <li><a href="#">Blue</a></li>--}}
{{--                                        <li><a href="#">Brown</a></li>--}}
{{--                                        <li><a href="#">Green</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </aside>
{{--                        <div class="toolbox-left">--}}
{{--                            <a href="#"--}}
{{--                               class="toolbox-item left-sidebar-toggle btn btn-sm btn-outline btn-primary btn-rounded btn-icon-right d-lg-none">--}}
{{--                                Filter<i class="d-icon-arrow-right"></i></a>--}}
{{--                            <div class="toolbox-item toolbox-sort select-menu">--}}
{{--                                <select name="orderby" class="form-control">--}}
{{--                                    <option value="default" selected="selected">Default Sorting</option>--}}
{{--                                    <option value="popularity">Most Popular</option>--}}
{{--                                    <option value="rating">Average rating</option>--}}
{{--                                    <option value="date">Latest</option>--}}
{{--                                    <option value="price-low">Sort forward price low</option>--}}
{{--                                    <option value="price-high">Sort forward price high</option>--}}
{{--                                    <option value="">Clear custom sort</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="toolbox-right">
                            <div class="toolbox-item toolbox-show select-box text-dark">
                                <label>show :</label>
                                <select name="count" class="form-control">
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="36">36</option>
                                </select>
                            </div>
                            <div class="toolbox-item toolbox-layout">
                                <a href="shop-list-mode.html" class="d-icon-mode-list btn-layout"></a>
                                <a href="shop.html" class="d-icon-mode-grid btn-layout active"></a>
                            </div>
                        </div>
                    </nav>
                    <div class="select-items">
                        <a href="#" class="filter-clean text-primary">Clean All</a>
                    </div>
                    <div class="row cols-2 cols-sm-3 cols-md-4 product-wrapper">

                        @foreach($products as $product)
                            <div class="product-wrap">
                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="{{ route('products.show', $product->id) }}">
{{--                                            @foreach ($product->getMedia('product-images') as $image)--}}
{{--                                                <img src="{{ $image->getUrl() }}" alt="Product Image" class="img-fluid mb-3">--}}
{{--                                            @endforeach--}}

                                            @if ($product->getFirstMediaUrl('product-images'))
                                                <img src="{{ $product->getFirstMediaUrl('product-images') }}" alt="{{ $product->name }}" width="300"
                                                     height="338">
                                            @else
                                                <img src="https://i.makeup.fr/i/i4/i4dfmpe8rxkj.png" alt="Product Image">
                                            @endif
                                            <img src="{{ $product->image ? asset('images/products/' . $product->image) : 'https://i.makeup.fr/i/i4/i4dfmpe8rxkj.png' }}" alt="{{ $product->name }}" width="300"
                                                 height="338">
                                        </a>
                                        <div class="product-action-vertical">
                                            <a href="#" class="btn-product-icon btn-cart" title="Select Options">
                                                <i class="d-icon-bag"></i>
                                            </a>
                                            <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"><i
                                                    class="d-icon-heart"></i></a>
                                        </div>
                                        <div class="product-action">
                                            <a href="#" class="btn-product btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </div>
                                    </figure>
                                    <div class="product-details">
                                        <h3 class="product-name">
                                            <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="product-price">
                                            <div class="product-price">
                                                <ins class="new-price">{{ number_format($product->price, 2) }} MAD</ins><del class="old-price">{{ number_format($product->old_price, 2) }} MAD</del>
                                            </div>
                                        </div>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width:90%"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                            <a href="{{ route('products.show', $product->id) }}" class="rating-reviews">({{ $product->reviews_count }} Avis)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <h1>No product</h1>


                    <nav class="toolbox toolbox-pagination mb-1">
{{--                        <p class="toolbox-item show-info d-block mb-2 mb-sm-0">Showing 1–12 of 24 Products</p>--}}
{{--                        <ul class="pagination">--}}
{{--                            <li class="page-item disabled">--}}
{{--                                <a class="page-link page-link-prev" href="#" aria-label="Previous" tabindex="-1"--}}
{{--                                   aria-disabled="true">--}}
{{--                                    <i class="d-icon-arrow-left"></i>Prev--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                            <li class="page-item">--}}
{{--                                <a class="page-link page-link-next" href="#" aria-label="Next">--}}
{{--                                    Next<i class="d-icon-arrow-right"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
                    </nav>
                </div>
            </div>

        </main>

        <!-- End of Main -->
    </div>
@endsection('content')
