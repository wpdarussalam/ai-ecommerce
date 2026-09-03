<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource; // Import ini yang tadinya hilang
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 1)
            ->with('children')
            ->orderBy('position', 'asc')
            ->get();

        return CategoryResource::collection($categories);
    }
}
