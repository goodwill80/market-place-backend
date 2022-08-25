<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // GET ALL PRODUCTS
    public function index () {
        return Product::all();
    }

    // CREATE NEW PRODUCT
    public function store (Request $request) {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required'
        ]);
        return Product::create($request->all());
    }

    // GET PRODUCT BY ID
    public function show (Product $product) {
        return $product;
    }

    // UPDATE A PRODUCT
    public function update (Product $product, Request $request) {
        $product->update($request->all());
        return $product;
    }

    // DELETE A PRODUCT
    public function destroy (Product $product) {
        return $product->delete();
    }

    public function search ($name) {
        // $searchParam = $request->query('name');
        return Product::where('name', 'like', '%' . $name . '%')
                        ->orWhere('description', 'like', '%' . $name . '%')
                        ->orWhere('slug', 'like', '%' . $name . '%')
                        ->get();
    }
}
