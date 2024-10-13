@extends('.__base_main')

@section('content')


    <div class="page-wrapper">

        <main class="main checkout">
            <div class="page-content pt-7 pb-10 mb-10">
                <div class="step-by pr-4 pl-4">
                    <h3 class="title title-simple title-step"><a href="{{route('cart.show')}}">1. Panier</a></h3>
                    <h3 class="title title-simple title-step active"><a href="{{route('checkout')}}">2. Paiement</a></h3>
                    <h3 class="title title-simple title-step"><a href="{{route('thankyou')}}">3. Commande Complète</a></h3>
                </div>
                <div class="container mt-7">
                    <form action="{{ route('order.store') }}" method="POST" class="form">
                        @csrf

                        <div class="row">
                            <!-- Customer Details Section -->
                            <div class="col-lg-7 mb-6 mb-lg-0 pr-lg-4">
                                <h3 class="title title-simple text-left text-uppercase">Billing Details</h3>

                                <!-- First Name & Last Name -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Nom Complete *</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required />
                                    </div>
{{--                                    <div class="col-xs-6">--}}
{{--                                        <label>Nom *</label>--}}
{{--                                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required />--}}
{{--                                    </div>--}}
                                </div>

                                <!-- Company Name -->
                                <label>Nom de l'entreprise (Optionnel)</label>
                                <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" />

                                <!-- Country -->
                                <label>Pays / Région *</label>
                                <div class="select-box">
                                    <select name="country" class="form-control" required>
                                        <option value="fr" {{ old('country') == 'fr' ? 'selected' : '' }}>Maroc</option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <label>Adresse *</label>
                                <input type="text" class="form-control" name="address1" value="{{ old('address1') }}" required placeholder="Numéro et nom de la rue" />
                                <input type="text" class="form-control" name="address2" value="{{ old('address2') }}" placeholder="Appartement, suite, unité, etc. (optionnel)" />

                                <!-- City & State -->
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>Ville *</label>
                                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required />
                                    </div>
                                    <div class="col-xs-6">
                                        <label>Code Postal *</label>
                                        <input type="text" class="form-control" name="zip" value="{{ old('zip') }}" required />
                                    </div>
                                </div>

                                <!-- ZIP & Phone -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Téléphone *</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required />
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <label>Adresse e-mail *</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required />

                                <!-- Additional Information -->
                                <h2 class="title title-simple text-uppercase text-left">Informations supplémentaires</h2>
                                <label>Notes de commande (Optionnel)</label>
                                <textarea class="form-control pb-2 pt-2 mb-0" name="order_notes" cols="30" rows="5">{{ old('order_notes') }}</textarea>
                            </div>

                            <!-- Order Summary Section -->
                            <aside class="col-lg-5 sticky-sidebar-wrapper">
                                <div class="sticky-sidebar mt-1">
                                    <div class="summary pt-5">
                                        <h3 class="title title-simple text-left text-uppercase">Votre Commande</h3>
                                        <table class="order-table">
                                            <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cartItems as $item)
                                                <tr>
                                                    <td>{{ $item->product->name }} × {{ $item->quantity }}</td>
                                                    <td>{{ $item->product->price * $item->quantity }} MAD</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td>Total:</td>
                                                <td>{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }} MAD</td>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <button type="submit" class="btn btn-dark btn-rounded btn-order">Passer la commande</button>
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
