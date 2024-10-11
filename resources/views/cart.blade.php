@extends('.__base_main')

@section('content')
    <main class="main cart">
        <div class="page-content pt-7 pb-10">
            <div class="step-by pr-4 pl-4">
                <h3 class="title title-simple title-step active"><a href="{{route('cart.show')}}">1. Shopping Cart</a></h3>
                <h3 class="title title-simple title-step"><a href="{{route('checkout')}}">2. Checkout</a></h3>
                <h3 class="title title-simple title-step"><a href="{{route('thankyou')}}">3. Order Complete</a></h3>
            </div>
            <div class="container mt-7 mb-2">
                <div class="row">
                    <div class="col-lg-8 col-md-12 pr-lg-4">
                        <table class="shop-table cart-table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th></th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="cart-items">
                            @foreach($cartItems as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="product-thumbnail">
                                        <figure>
                                            <img src="{{ $item->product->getFirstMediaUrl('product-images')}}" width="100" height="100" alt="{{$item->product->name}}">
                                        </figure>
                                    </td>
                                    <td class="product-name">
                                        <div class="product-name-section">
                                            <a href="#">{{ $item->product->name }}</a>
                                        </div>
                                    </td>
                                    <td class="product-price">{{ $item->product->price }} MAD</td>
                                    <td class="product-quantity">
                                        <div class="input-group">
{{--                                            <button class="quantity-minus d-icon-minus"></button>--}}
                                            <input class="quantity form-control disabled" type="number" min="1" value="{{ $item->quantity }}" data-product-id="{{ $item->product->id }}">
{{--                                            <button class="quantity-plus d-icon-plus"></button>--}}
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <span class="amount">${{ $item->product->price * $item->quantity }}</span>
                                    </td>
                                    <td class="product-close">
                                        <button class="btn btn-danger btn-sm remove-item" data-product-id="{{ $item->product->id }}">Remove</button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <aside class="col-lg-4">
                        <div class="summary mb-4">
                            <h3 class="summary-title">Cart Totals</h3>
                            <table class="total">
                                <tr class="summary-subtotal">
                                    <td>Subtotal</td>
                                    <td id="cart-subtotal">${{ $total }}</td>
                                </tr>
                            </table>
                            <a href="{{route('checkout')}}" class="btn btn-dark btn-rounded btn-checkout">Proceed to checkout</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Update quantity
            $('.quantity').on('change', function() {
                var cartRow = $(this).closest('tr');
                var cartId = cartRow.data('id');
                var quantity = $(this).val();

                $.ajax({
                    url: '{{ route("cart.update") }}',
                    method: 'POST',
                    data: {
                        cart_id: cartId,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload(); // Reload page to reflect changes
                    }
                });
            });

            // Remove item
            $('.remove-item').on('click', function() {
                var cartRow = $(this).closest('tr');
                var cartId = cartRow.data('id');

                $.ajax({
                    url: '/cart/remove/' + cartId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload(); // Reload page to reflect changes
                    }
                });
            });
        });
    </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Quantity Change Buttons
                document.querySelectorAll('.quantity-minus, .quantity-plus').forEach(button => {
                    button.addEventListener('click', function () {
                        const quantityInput = this.closest('.input-group').querySelector('.quantity');
                        let currentQuantity = parseInt(quantityInput.value);
                        const productId = quantityInput.getAttribute('data-product-id');
                        const productPrice = parseFloat(this.closest('tr').querySelector('.product-price').textContent.replace('$', ''));

                        if (this.classList.contains('quantity-plus')) {
                            quantityInput.value = currentQuantity + 1;
                        } else if (this.classList.contains('quantity-minus') && currentQuantity > 1) {
                            quantityInput.value = currentQuantity - 1;
                        }

                        // Calculate new subtotal
                        const newSubtotal = (productPrice * parseInt(quantityInput.value)).toFixed(2);
                        const subtotalCell = this.closest('tr').querySelector('.product-subtotal .amount');
                        subtotalCell.textContent = '$' + newSubtotal;

                        // Prepare the data for AJAX request to update quantity
                        const updateData = {
                            product_id: productId,
                            quantity: quantityInput.value
{{--                            _token: '{{ csrf_token() }}'--}}
                        };
                        console.log(JSON.stringify(updateData));
                        // alert(65)
                        // Send AJAX request to update product quantity in cart

                        fetch('cart/update', {
                            method: 'GET',
                            // headers: {
                            //     'Content-Type': 'application/json',
                            //     // 'X-CSRF-TOKEN': updateData._token
                            // },
                            // body: JSON.stringify(updateData)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'Quantity updated!') {
                                    alert('Quantity updated!')
                                    // Optionally, you can also update the cart total if needed
                                    // For example, you could recalculate the total for all items here
                                } else {
                                    console.error('Failed to update quantity:', data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                });
            });
        </script>

@endsection
