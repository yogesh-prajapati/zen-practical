<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductMaster;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductMaster::all();
        return view('customer', compact('products'));
    }

    public function getProduct($id)
    {
        $product = ProductMaster::where('product_id',$id)->first();
        return response()->json(['data' => $product, 'status' => 'success']);
    }
}
