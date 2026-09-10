<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Cari produk berdasarkan slug atau ID
        $product = Product::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        
        // Ambil produk terkait berdasarkan kategori yang sama
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(6)
            ->get();

        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }
}