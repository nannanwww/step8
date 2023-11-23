<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductCreateController extends Controller
{
    public function create() {
        $companies = Product::pluck('company')->unique();
        return view('products.create', ['companies' => $companies]); }

    public function store(Request $request) {
        $request->validate([
            'product_name' => 'required|max:20',
            'company' => 'required|max:20',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable|max:140',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = new Product;
        $product->product_name = $request->input("product_name");
        $product->company = $request->input("company");
        $product->price = $request->input("price");
        $product->stock = $request->input("stock");
        $product->description = $request->input("description");

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName); // 画像を保存
            $product->image = 'storage/images/' . $imageName;
        }
        
        $product->save();

        return redirect('/products/create')->with('success', '商品が登録されました'); }
}
