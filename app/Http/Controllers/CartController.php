<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Add to Cart Method
//    public function addToCart(Request $request)
//    {
//        $validatedData = $request->validate([
//            'product_id' => 'required|exists:products,id',
//            'quantity' => 'required|integer|min:1'
//        ]);
//
//        $sessionId = $request->session()->getId(); // Get the current session ID
//
//        // Check if the item already exists in the cart
//        $cartItem = Cart::where('session_id', $sessionId)
//            ->where('product_id', $validatedData['product_id'])
//            ->first();
//
//        if ($cartItem) {
//            // If item exists, increment the quantity
//            $cartItem->quantity += $validatedData['quantity'];
//            $cartItem->save();
//        } else {
//            // Create a new cart item
//            Cart::create([
//                'session_id' => $sessionId,
//                'product_id' => $validatedData['product_id'],
//                'quantity' => $validatedData['quantity']
//            ]);
//        }
//
//        return response()->json(['success' => 'Item added to cart successfully!']);
//    }

    // Get Cart Items Method for Ajax Calls
    public function getCartItems(Request $request)
    {
        $sessionId = $request->session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();

        $total = 0;
        $html = '';

        foreach ($cartItems as $item) {
            $html .= "
                <div class='cart-item'>
                    <p>{$item->product->name} - Quantity: {$item->quantity}</p>
                    <button class='remove-item' data-id='{$item->id}'>Remove</button>
                </div>
            ";
            $total += $item->product->price * $item->quantity;
        }

        return response()->json(['html' => $html, 'total' => $total]);
    }

    // Remove Item from Cart
//    public function removeCartItem($id, Request $request)
//    {
//        $sessionId = $request->session()->getId();
//        $cartItem = Cart::where('session_id', $sessionId)->where('id', $id)->firstOrFail();
//        $cartItem->delete();
//
//        return response()->json(['success' => 'Item removed from cart!']);
//    }

    public function showCart(Request $request)
    {
        $sessionId = $request->session()->getId();

        // Fetch cart items based on session ID
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();


        $sessionId = $request->session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    // Add a product to the cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:shop_products,id', // Ensure the table name is correct here
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id); // This should use your Product model

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity; // Update quantity
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'photo' => $product->image, // Assuming you have an image field
            ];
        }


        $sessionId = $request->session()->getId();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Check if product already exists in the cart
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json(['success' => 'Item added to cart successfully!']);
    }

    // Update cart item quantity
    public function updateCartItem(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::find($request->input('cart_id'));
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return response()->json(['success' => 'Cart item updated successfully!']);
    }

    // Remove an item from the cart
    public function removeCartItem($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return response()->json(['success' => 'Item removed from cart successfully!']);

    }

    public function updateQuantity(Request $request)
    {

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Retrieve current cart items
        $cartItems = session('cart.items', []);

        // Check if the product exists in the cart
        if (isset($cartItems[$productId])) {
            // Update the quantity
            $cartItems[$productId]['quantity'] = $quantity;

            // Update the session with the new cart items
            session(['cart.items' => $cartItems]);

            // Recalculate the total price
            $cartTotal = 0;
            foreach ($cartItems as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }

            // Update the session total
            session(['cart.total' => $cartTotal]);

            return response()->json([
                'status' => 'Quantity updated!',
                'cartTotal' => number_format($cartTotal, 2), // Format the total to 2 decimal places
            ]);
        }

        return response()->json(['status' => 'Product not found in cart.'], 404);
    }

    public function checkout(Request $request)
    {
        $sessionId = $request->session()->getId();

        // Fetch cart items based on session ID
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();


        $sessionId = $request->session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('pages.checkout', compact('cartItems', 'total'));

    }
}
