<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Model::unguard();

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) use ($request) {
            $sessionId = $request->session()->getId();
            $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();

            // Calculate the total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $view->with([
                'cartItems' => $cartItems,
                'cartTotal' => $total
            ]);
        });
    }
}
