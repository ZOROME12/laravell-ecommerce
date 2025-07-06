<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem; // use CartItem instead of Cart

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                // Use CartItem model and query the cart_items table
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            }

            $view->with('cartItemCount', $cartCount);
        });
    }
}
