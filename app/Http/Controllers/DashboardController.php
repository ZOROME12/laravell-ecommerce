<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem; 
use App\Models\Order; 
use App\Models\CustomShirt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get count of user's orders
        $orderCount = Order::where('user_id', $user->id)->count();

        // Get count of user's cart items
        $cartItemCount = CartItem::where('user_id', $user->id)->count();
        
        // Get products for "Recommended" section
        $products = Product::latest()->take(3)->get(); 

        // Get recent orders for the modal
        $userOrders = Order::where('user_id', $user->id)
                           ->latest() 
                           ->take(10)
                           ->get();

        // <-- THIS IS THE FIX: Changed 'user_id' to 'email'
        $customRequests = CustomShirt::where('email', $user->email) 
                                     ->latest()
                                     ->take(10)
                                     ->get();

        return view('dashboard', [
            'orderCount' => $orderCount,
            'cartItemCount' => $cartItemCount,
            'products' => $products,
            'userOrders' => $userOrders,
            'customRequests' => $customRequests 
        ]);
    }
}