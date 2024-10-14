@extends('.__base_main')

@section('content')

    <div class="page-wrapper">

        <main class="main cart">
            <div class="page-content pt-7 pb-10">
                <div class="step-by pr-4 pl-4">
                    <h3 class="title title-simple title-step active">1. Shopping Cart</h3>
                    <h3 class="title title-simple title-step">2. Checkout</h3>
                    <h3 class="title title-simple title-step">3. Order Complete</h3>
                </div>
                <div class="container mt-7 mb-2">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 pr-lg-4">
                            <table class="shop-table cart-table">
                                <thead>
                                <tr>
                                    <th><span>Product</span></th>
                                    <th></th>
                                    <th><span>Price</span></th>
                                    <th><span>quantity</span></th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="product-thumbnail">
                                        <figure>
                                            <a href="product-simple.html">
                                                <img src="images/product-img/p1.jpg" width="100" height="100"
                                                     alt="product">
                                            </a>
                                        </figure>
                                    </td>
                                    <td class="product-name">
                                        <div class="product-name-section">
                                            <a href="product-simple.html">DIKSON COLOR</a>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <span class="amount">$129.99</span>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="input-group">
                                            <button class="quantity-minus d-icon-minus"></button>
                                            <input class="quantity form-control" type="number" min="1"
                                                   max="1000000">
                                            <button class="quantity-plus d-icon-plus"></button>
                                        </div>
                                    </td>
                                    <td class="product-price">
                                        <span class="amount">$129.99</span>
                                    </td>
                                    <td class="product-close">
                                        <a href="#" class="product-remove" title="Remove this product">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="product-thumbnail">
                                        <figure>
                                            <a href="product-simple.html">
                                                <img src="images/product-img/p2.png" width="100" height="100"
                                                     alt="product">
                                            </a>
                                        </figure>

                                    </td>
                                    <td class="product-name">
                                        <div class="product-name-section">
                                            <a href="product-simple.html">Women Beautiful Headgear</a>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <span class="amount">$98.00</span>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="input-group">
                                            <button class="quantity-minus d-icon-minus"></button>
                                            <input class="quantity form-control" type="number" min="1"
                                                   max="1000000">
                                            <button class="quantity-plus d-icon-plus"></button>
                                        </div>
                                    </td>
                                    <td class="product-price">
                                        <span class="amount">$98.00</span>
                                    </td>
                                    <td class="product-close">
                                        <a href="#" class="product-remove" title="Remove this product">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="cart-actions mb-6 pt-4">
                                <a href="demo6-product.html" class="btn btn-dark btn-md btn-rounded btn-icon-left mr-4 mb-4"><i
                                        class="d-icon-arrow-left"></i>Continue Shopping</a>
                                <button type="submit"
                                        class="btn btn-outline btn-dark btn-md btn-rounded btn-disabled">Update
                                    Cart</button>
                            </div>
{{--                            <div class="cart-coupon-box mb-8">--}}
{{--                                <h4 class="title coupon-title text-uppercase ls-m">Coupon Discount</h4>--}}
{{--                                <input type="text" name="coupon_code"--}}
{{--                                       class="input-text form-control text-grey ls-m mb-4" id="coupon_code" value=""--}}
{{--                                       placeholder="Enter coupon code here...">--}}
{{--                                <button type="submit" class="btn btn-md btn-dark btn-rounded btn-outline">Apply--}}
{{--                                    Coupon</button>--}}
{{--                            </div>--}}
                        </div>
                        <aside class="col-lg-4 sticky-sidebar-wrapper">
                            <div class="sticky-sidebar" data-sticky-options="{'bottom': 20}">
                                <div class="summary mb-4">
                                    <h3 class="summary-title text-left">Cart Totals</h3>
                                    <table class="shipping">
                                        <tr class="summary-subtotal">
                                            <td>
                                                <h4 class="summary-subtitle">Subtotal</h4>
                                            </td>
                                            <td>
                                                <p class="summary-subtotal-price">$426.99</p>
                                            </td>
                                        </tr>
                                        <tr class="sumnary-shipping shipping-row-last">
                                            <td colspan="2">
                                                <h4 class="summary-subtitle">Calculate Shipping</h4>
                                                <ul>
                                                    <li>
                                                        <div class="custom-radio">
                                                            <input type="radio" id="flat_rate" name="shipping"
                                                                   class="custom-control-input" checked>
                                                            <label class="custom-control-label" for="flat_rate">Flat
                                                                rate</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-radio">
                                                            <input type="radio" id="free-shipping" name="shipping"
                                                                   class="custom-control-input">
                                                            <label class="custom-control-label" for="free-shipping">Free
                                                                shipping</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="custom-radio">
                                                            <input type="radio" id="local_pickup" name="shipping"
                                                                   class="custom-control-input">
                                                            <label class="custom-control-label" for="local_pickup">Local
                                                                pickup</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="shipping-address">
                                        <label>Shipping to <strong>CA.</strong></label>
                                        <div class="select-box">
                                            <select name="country" class="form-control">
                                                <option value="us" selected>United States (US)</option>
                                                <option value="uk"> United Kingdom</option>
                                                <option value="fr">France</option>
                                                <option value="aus">Austria</option>
                                            </select>
                                        </div>
                                        <div class="select-box">
                                            <select name="country" class="form-control">
                                                <option value="us" selected>California</option>
                                                <option value="uk">Alaska</option>
                                                <option value="fr">Delaware</option>
                                                <option value="aus">Hawaii</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control" name="code" placeholder="Town / City" />
                                        <input type="text" class="form-control" name="code" placeholder="ZIP" />
                                        <a href="#" class="btn btn-md btn-dark btn-rounded btn-outline">Update
                                            totals</a>
                                    </div>
                                    <table class="total">
                                        <tr class="summary-subtotal">
                                            <td>
                                                <h4 class="summary-subtitle">Total</h4>
                                            </td>
                                            <td>
                                                <p class="summary-total-price ls-s">$426.99</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <a href="{{route('checkout')}}" class="btn btn-dark btn-rounded btn-checkout">Proceed to
                                        checkout</a>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>

        </main>
        <!-- End Main -->

    </div>
@endsection
