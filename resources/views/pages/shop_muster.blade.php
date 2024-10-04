@extends('.__base')

@section('content')


    <div class="page-wrapper">


        <main class="main">
            <div class="page-header"
                 style="background-image: url('images/demos/demo19/page-header.jpg'); background-color: #3C63A4;">
                <h1 class="page-title">Muster Shop</h1>
                <ul class="breadcrumb pb-0">
                    <li><a href="demo19.html"><i class="fa-solid fa-house"></i></a></li>
                    <li class="delimiter">/</li>
                    <li>Dikson Shop</li>
                </ul>
            </div>
            <!-- End PageHeader -->
            <div class="page-content mb-10 pb-2">
                <div class="container-fluid">
                    <div class="toolbox-wrap">
                        <aside class="sidebar sidebar-fixed shop-sidebar closed">
                            <div class="sidebar-overlay">
                                <a class="sidebar-close" href="#"><i class="d-icon-times"></i></a>
                            </div>
                            <div class="sidebar-content">
                                <div class="mb-0 mb-lg-4">
                                    <div class="filter-actions">
                                        <a href="#"
                                           class="sidebar-toggle-btn toggle-remain btn btn-sm btn-outline btn-primary">Filter<i
                                                class="d-icon-arrow-left"></i></a>
                                        <a href="#" class="filter-clean text-primary">Clean All</a>
                                    </div>
                                    <!-- <a href="#" class="filter-clean text-primary">Clean All</a> -->
                                    <div class="row cols-lg-4">
                                        <div class="widget">
                                            <h3 class="widget-title">Size</h3>
                                            <ul class="widget-body filter-items">
                                                <li><a href="#">Small</a></li>
                                                <li><a href="#">Medium</a></li>
                                                <li><a href="#">Large</a></li>
                                                <li><a href="#">Extra Large</a></li>
                                            </ul>
                                        </div>
                                        <div class="widget">
                                            <h3 class="widget-title">Color</h3>
                                            <ul class="widget-body filter-items">
                                                <li><a href="#">Black</a></li>
                                                <li><a href="#">Blue</a></li>
                                                <li><a href="#">Green</a></li>
                                                <li><a href="#">Gray</a></li>
                                            </ul>
                                        </div>
                                        <div class="widget">
                                            <h3 class="widget-title">Price</h3>
                                            <ul class="widget-body filter-items filter-price">
                                                <li><a href="#">All<span>(10)</span></a></li>
                                                <li><a href="#">$0.00 - $100.00<span>(1)</span></a></li>
                                                <li><a href="#">$100.00 - $200.00<span>(9)</span></a></li>
                                                <li><a href="#">$200.00+<span>(3)</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="widget">
                                            <h3 class="widget-title">Tags</h3>
                                            <div class="widget-body pt-2">
                                                <a href="#" class="tag">Bag</a>
                                                <a href="#" class="tag">Classic</a>
                                                <a href="#" class="tag">Converse</a>
                                                <a href="#" class="tag">Diesel</a>
                                                <a href="#" class="tag">Fit</a>
                                                <a href="#" class="tag">Green</a>
                                                <a href="#" class="tag">Jeans</a>
                                                <a href="#" class="tag">Jumper</a>
                                                <a href="#" class="tag">Leather</a>
                                                <a href="#" class="tag">Man</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <div class="toolbox sticky-toolbox sticky-content fix-top">
                            <div class="toolbox-left">
                                <a href="#"
                                   class="toolbox-item left-sidebar-toggle btn btn-outline btn-primary btn-icon-left font-primary">
                                    <i class="fa-solid fa-filter"></i>Filter</a>
                                <p class="toolbox-item show-info mr-sm-auto">
                                    Affichage de 1 à 12 sur 30 produits</p>
                            </div>
                            <div class="toolbox-right">
                                <div class="toolbox-item toolbox-sort select-box">
                                    <label>Trier par :</label>
                                    <select name="orderby" class="form-control">
                                        <option value="default">Par défaut</option>
                                        <option value="popularity" selected="selected">Les plus populaires</option>
                                        <option value="rating">Note moyenne</option>
                                        <option value="date">Les plus récents</option>
                                        <option value="price-low">Prix croissant</option>
                                        <option value="price-high">Prix décroissant</option>
                                        <option value="">Réinitialiser le tri personnalisé</option>
                                    </select>
                                </div>
{{--                                <div class="toolbox-item toolbox-layout">--}}
{{--                                    <a href="shop-list-mode.html" class=" btn-layout"><i class="fa-solid fa-list"></i></a>--}}
{{--                                    <a href="shop.html" class=" btn-layout active"><i class="fa-regular fa-square"></i></a>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="row cols-2 cols-sm-3 cols-md-4 cols-xl-6">
                        @foreach($products as $product)
                            <div class="col-6 col-md-3 mb-4"> <!-- Adjusted for 4 products per row -->
                                <div class="product-wrap">
                                    <div class="product text-center">
                                        <figure class="product-media">
                                            <a href="{{ route('products.show', $product->id) }}">
                                                <img src="{{ $product->image ? asset('images/products/' . $product->image) : 'https://d-themes.com/html/riode/images/product/product-1-1-580x652.jpg' }}" alt="{{ $product->name }}" width="280" height="315">
                                            </a>
                                            <div class="product-action-vertical">
                                                {{--                                                            <a href="{{ route('cart.add', $product->id) }}" class="btn-product-icon btn-cart" title="Add to cart">--}}
                                                {{--                                                                <i class="d-icon-bag"></i>--}}
                                                {{--                                                            </a>--}}
                                                {{--                                                            <a href="{{ route('wishlist.add', $product->id) }}" class="btn-product-icon btn-wishlist" title="Add to wishlist">--}}
                                                {{--                                                                <i class="d-icon-heart"></i>--}}
                                                {{--                                                            </a>--}}
                                            </div>
                                            <div class="product-action">
                                                <a href="#" class="btn-product btn-quickview" title="Quick View">Voir</a>
                                            </div>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-name">
                                                <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                            </h3>
                                            <div class="product-price">
                                                <span class="price">{{ number_format($product->price, 2) }} MAD</span>
                                            </div>
                                            <div class="ratings-container">
                                                <div class="">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
{{--                                                    <span class="" style="width:{{ $product->rating * 20 }}%"></span>--}}
{{--                                                    <span class="tooltiptext tooltip-top">{{ $product->rating }} Stars</span>--}}
                                                </div>
                                                <a href="{{ route('products.show', $product->id) }}" class="rating-reviews">({{ $product->reviews_count }} Avis)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
{{--                    <div class="row cols-2 cols-sm-3 cols-md-4 cols-xl-6 product-wrapper">--}}
{{--                        <div class="shop-products">--}}
{{--                            <div class="product-list">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <nav class="toolbox toolbox-pagination justify-content-center">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link page-link-prev" href="#" aria-label="Previous" tabindex="-1"
                                   aria-disabled="true">
                                    <i class="fa-solid fa-arrow-left"></i>Précédente
                                </a>
                            </li>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link page-link-next" href="#" aria-label="Suivante">
                                    Suivante<i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </main>
        <!-- End Main -->

    </div>

@endsection
