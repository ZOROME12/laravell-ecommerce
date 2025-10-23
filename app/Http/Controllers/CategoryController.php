<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Get all categories (for dashboard, dropdowns, charts, etc.)
    public function index()
    {
        // Return all categories as JSON
        return response()->json(Category::all());
    }
}