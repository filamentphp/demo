<?php

namespace App\Http\Controllers;

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
}
