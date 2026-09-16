<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil kategori & produk terbaru dari database
        $categories = Category::all();
       //$products = Product::where('status', 'published')->get();
       $products = Product::where('status', true)->get();

        //return view('frontend.home', compact('categories', 'products'));
       return view('frontend.home');
       

    }
}