<?php

namespace App\Http\Controllers;

use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function showMusterProducts()
    {
        $products = Product::whereHas('categories', function ($query) {
            $query->where('name', 'muster');
        })->withCount('comments')
            ->get();

        return view('pages.shop_muster', ['products' => $products]);
    }

    public function showDiksonProducts()
    {
        $products = Product::whereHas('categories', function ($query) {
            $query->where('name', 'dikson');
        })->withCount('comments')
            ->get();

        return view('pages.shop_dikson', ['products' => $products]);
    }

    public function cart()
    {
        $products = Product::whereHas('categories', function ($query) {
            $query->where('name', 'dikson');
        });

        return view('pages.cart', ['products' => $products]);
    }



    public function tahnkyou()
    {
        $products = Product::whereHas('categories', function ($query) {
            $query->where('name', 'dikson');
        });

        return view('pages.thank_you', ['products' => $products]);
    }

    public function store(Request $request)
    {


        // Validate form inputs
        $validated = $request->validate([
//            'shop_customer_id' => 'string|max:255',
            'number' => 'string|max:255',
            'total_price' => 'string|max:255',
            'status' => 'string|max:255',
            'currency' => 'string|max:255',
            'shipping_price' => 'string|max:255',
            'shipping_method' => 'string|max:255',
            'notes' => 'string|max:255',
//            'company-name' => 'nullable|string|max:255',
//            'country' => 'required|string',
//            'address1' => 'required|string|max:255',
//            'address2' => 'nullable|string|max:255',
//            'city' => 'required|string|max:255',
//            'state' => 'required|string|max:255',
//            'zip' => 'required|string|max:10',
//            'phone' => 'required|string|max:15',
//            'email-address' => 'required|email|max:255',
//            'order-notes' => 'nullable|string',
//            'terms-condition' => 'accepted',
        ]);

        $validatedCustomer = $request->validate([
            'company-name' => 'nullable|string|max:255',
            'country' => 'required|string',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
            'email-address' => 'required|email|max:255',
            'order-notes' => 'nullable|string',
            'terms-condition' => 'accepted',
        ]);

        Customer::create([
            'last_name' => $validated['last-name'],
            'company_name' => $validated['company-name'],
            'country' => $validated['country'],
            'address1' => $validated['address1'],
            'address2' => $validated['address2'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'phone' => $validated['phone'],
            'email' => $validated['email-address'],
            'order_notes' => $validated['order-notes'],
            'create_account' => $request->has('create-account'),
            'different_address' => $request->has('different-address'),
        ]);

        Order::create([
//            'shop_customer_id' => $validated['shop_customer_id'],
            'number' => $validated['number'],
            'total_price' => $validated['total_price'],
            'status' => $validated['status'],
            'currency' => $validated['currency'],
            'shipping_price' => $validated['shipping_price'],
            'shipping_method' => $validated['shipping_method'],
            'notes' => $validated['notes'],
//            'last_name' => $validated['last-name'],
//            'company_name' => $validated['company-name'],
//            'country' => $validated['country'],
//            'address1' => $validated['address1'],
//            'address2' => $validated['address2'],
//            'city' => $validated['city'],
//            'state' => $validated['state'],
//            'zip' => $validated['zip'],
//            'phone' => $validated['phone'],
//            'email' => $validated['email-address'],
//            'order_notes' => $validated['order-notes'],
//            'create_account' => $request->has('create-account'),
//            'different_address' => $request->has('different-address'),
        ]);

        dd($request);
        return redirect()->back()->with('success', 'Order placed successfully.');
    }
}
