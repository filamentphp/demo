@extends('.__base_main')

@section('content')


    <div class="page-wrapper">

        <main class="main checkout">
            <div class="page-content pt-7 pb-10 mb-10">
                <div class="step-by pr-4 pl-4">
                    <h3 class="title title-simple title-step"><a href="{{route('cart.show')}}">1. Shopping Cart</a></h3>
                    <h3 class="title title-simple title-step active"><a href="{{route('checkout')}}">2. Checkout</a></h3>
                    <h3 class="title title-simple title-step"><a href="{{route('thankyou')}}">3. Order Complete</a></h3>
                </div>
                <div class="container mt-7">
                    <form action="{{ route('order.store') }}" method="POST" class="form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-7 mb-6 mb-lg-0 pr-lg-4">
                                <h3 class="title title-simple text-left text-uppercase">Billing Details</h3>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>First Name *</label>
                                        <input type="text" class="form-control" name="first-name" value="{{ old('first-name') }}" required="" />
                                    </div>
                                    <div class="col-xs-6">
                                        <label>Last Name *</label>
                                        <input type="text" class="form-control" name="last-name" value="{{ old('last-name') }}" required="" />
                                    </div>
                                </div>
                                <label>Company Name (Optional)</label>
                                <input type="text" class="form-control" name="company-name" value="{{ old('company-name') }}" />

                                <label>Country / Region *</label>
                                <div class="select-box">
                                    <select name="country" class="form-control" required>
                                        <option value="fr" {{ old('country') == 'mr' ? 'selected' : '' }}>Maroc</option>
                                    </select>
                                </div>

                                <label>Street Address *</label>
                                <input type="text" class="form-control" name="address1" value="{{ old('address1') }}" required="" placeholder="House number and street name" />
                                <input type="text" class="form-control" name="address2" value="{{ old('address2') }}" placeholder="Apartment, suite, unit, etc. (optional)" />

                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>Town / City *</label>
                                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required="" />
                                    </div>
                                    <div class="col-xs-6">
                                        <label>State *</label>
                                        <input type="text" class="form-control" name="state" value="{{ old('state') }}" required="" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>ZIP *</label>
                                        <input type="text" class="form-control" name="zip" value="{{ old('zip') }}" required="" />
                                    </div>
                                    <div class="col-xs-6">
                                        <label>Phone *</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required="" />
                                    </div>
                                </div>

                                <label>Email Address *</label>
                                <input type="email" class="form-control" name="email-address" value="{{ old('email-address') }}" required="" />

                                <div class="form-checkbox mb-0">
                                    <input type="checkbox" class="custom-checkbox" id="create-account" name="create-account" {{ old('create-account') ? 'checked' : '' }}>
                                    <label class="form-control-label ls-s" for="create-account">Create an account?</label>
                                </div>

                                <div class="form-checkbox mb-6">
                                    <input type="checkbox" class="custom-checkbox" id="different-address" name="different-address" {{ old('different-address') ? 'checked' : '' }}>
                                    <label class="form-control-label ls-s" for="different-address">Ship to a different address?</label>
                                </div>

                                <h2 class="title title-simple text-uppercase text-left">Additional Information</h2>
                                <label>Order Notes (Optional)</label>
                                <textarea class="form-control pb-2 pt-2 mb-0" name="order-notes" cols="30" rows="5" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order-notes') }}</textarea>
                            </div>

                            <aside class="col-lg-5 sticky-sidebar-wrapper">
                                <div class="sticky-sidebar mt-1" data-sticky-options="{'bottom': 50}">
                                    <div class="summary pt-5">
                                        <h3 class="title title-simple text-left text-uppercase">Your Order</h3>
                                        <table class="order-table">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cartItems as $item)
                                                <tr data-id="{{ $item->id }}">
                                                    <td class="product-name">
                                                        <div class="product-name-section">
                                                            <a href="#">{{ $item->product->name }}</a>
                                                            <span class="product-quantity">×&nbsp;{{ $item->quantity }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="product-total text-body">
                                                        ${{ $item->product->price * $item->quantity }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="summary-subtotal">
                                                <td>
                                                    <h4 class="summary-subtitle">Total</h4>
                                                </td>
                                                <td class="summary-subtotal-price pb-0 pt-0">
                                                    ${{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <div class="payment accordion radio-type">
                                            <h4 class="summary-subtitle ls-m pb-3">Payment Methods</h4>
                                            <div class="card">
                                                <div class="card-header">
                                                    <a href="#collapse1" class="collapse text-body text-normal ls-m">Check payments</a>
                                                </div>
                                                <div id="collapse1" class="expanded" style="display: block;">
                                                    <div class="card-body ls-m">
                                                        Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header">
                                                    <a href="#collapse2" class="expand text-body text-normal ls-m">Cash on delivery</a>
                                                </div>
                                                <div id="collapse2" class="collapsed">
                                                    <div class="card-body ls-m">
                                                        Pay with cash upon delivery.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-checkbox mt-4 mb-5">
                                            <input type="checkbox" class="custom-checkbox" id="terms-condition" name="terms-condition" />
                                            <label class="form-control-label" for="terms-condition">
                                                I have read and agree to the website <a href="#">terms and conditions</a>*
                                            </label>
                                        </div>

                                        <button type="submit" class="btn btn-dark btn-rounded btn-order">Place Order</button>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </form>
                </div>

            </div>

        </main>
        <!-- End Main -->
    </div>


@endsection('content')
