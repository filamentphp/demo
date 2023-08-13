<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Slider;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Models\Shop\Customer;
use App\Models\Shop\OrderAddress;
use App\Models\Shop\OrderItem;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class FrontendController extends Controller
{
    public function index()
    {
        $slider = Slider::where('status', 1)->get();
        $products = Product::with('media', 'categories', 'brand')->where('is_visible', 1)->get();
        return Inertia::render('Index',[
            'slider' =>  $slider,
            'products' => $products
        ]);
    }

    public function viewProduct($slug)
    {
        $pageContent = Product::with('media', 'categories', 'brand', 'team.players')->where('slug', $slug)->first();

        return Inertia::render('Products/Product', [
            'product' => $pageContent
        ]);
    }

    public function cart()
    {
        return Inertia::render('Cart');
    }

    public function checkout()
    {
        return Inertia::render('Checkout');
    }

    public function orderConfirmation()
    {
        return Inertia::render('OrderConfirmation');
    }

    public function createOrder(Request $request)
    {

        $orderNumber = $this->generateOrderNumber();
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'town' => 'required|string',
            'county' => 'required|string',// Adjust options as needed
            'delivery_instruction' => 'nullable|string',
            'order' => 'required|array',
            'gender' => 'required',
            'delivery' => 'required',
            'total' => 'required',
            'deliverylocation' => 'nullable'
        ]);

        $customer = $this->createOrUpdateCustomer($validatedData);

        $order = $this->createOrderRecord($validatedData, $customer->id,  $orderNumber);

        return response()->json(['message' => 'Order created successfully', 'data' => $order]);
    }

    private function createOrUpdateCustomer($data)
    {
        $customer = Customer::where('email', $data['email'])->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'gender' => $data['gender'] ?? null, // Provide a default value or remove if not used
            ]);
        }

        return $customer;
    }

    private function createOrderRecord($data, $customerId,  $orderNumber)
    {

        $order = Order::create([
            'shop_customer_id' => $customerId,
            'number' => $orderNumber,
            'town' => $data['town'],
            'county' => $data['county'],
            'delivery' => $data['delivery'],
            'delivery_instruction' => $data['delivery_instruction'],
            'deliverylocation' => $data['deliverylocation'],
            'currency' => 'KES',
            'shipping_method' => $data['delivery'],
            'notes' => $data['delivery_instruction'],
            'total_price' => $data['total']
        ]);

        foreach($data['order'] as $cartItem)
        {
            $orderItems = OrderItem::create([
                'shop_order_id' => $order->id,
                'shop_product_id' => $cartItem['id'],
                'qty' => $cartItem['quantity'],
                'unit_price' => $cartItem['price']
            ]);
        }


        // Handle order items here if present in $data['order']

        return $order;
    }

    private function generateOrderNumber()
    {
        $datePrefix = now()->format('Ymd'); // Get the current date as YYYYMMDD
        $count = Order::whereDate('created_at', now())->count() + 1;
        $orderNumber = $datePrefix . str_pad($count, 4, '0', STR_PAD_LEFT); // Format: YYYYMMDD0001

        return $orderNumber;
    }
}
