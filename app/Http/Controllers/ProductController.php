<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::query();
        $companies = Product::pluck('company')->unique();

        $keyword = $request->input('key_word');
        $company = $request->input('key_company');

        if ($keyword) {
            $products = $products->where(function($query) use ($keyword) {
                $query->where('product_name', 'like', '%' . $keyword . '%')
                      ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($company) {
            $products = $products->where('company', $company);
        }

        $products = $products->orderBy('product_name')->paginate(6);
        $request->session()->put('products_page', $products->currentPage());
        $request->session()->put('searchParams', $request->getQueryString());

        $errorMessage = '';

        if ($request->has('key_word') && $products->isEmpty()) {
            $errorMessage = '検索キーワードに一致する商品が見つかりませんでした。';
        }

        $requestParams = $request->getQueryString();
        
        return view('products.index', [
            'products' => $products,
            'companies' => $companies,
            'errorMessage' => $errorMessage,
            'requestParams' => $requestParams,
        ]);
    }

    



    public function create() 
    {
        $companies = Product::pluck('company')->unique();
        return view('products.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
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

        return redirect('/products');
    }

    public function showDetail($id) 
    {
        $product = Product::find($id); // 
    
        return view('products.detail', ['product'=> $product]);
    }

    public function delete($id) 
    {
        $product = Product::findOrFail($id);
        logger($product); 
    
        $product->delete();
        return redirect('/products');
    }

    public function edit($id) 
    {
        $product = Product::find($id); // 
        if (!$product) {
            // 商品が存在しない場合の処理
            abort(404);
        }
        return view('products.edit', ['product' => $product]);
    }



    public function update(Request $request, $id) 
    {
        $product = Product::find($id);

        $request->validate([
            'product_name' => 'required|max:20',
            'company' => 'required|max:20',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable|max:140',
            'image' => 'nullable|image|max:2048',
        ]);

        // 更新処理
        $product->product_name = $request->input("product_name");
        $product->company = $request->input("company");
        $product->price = $request->input("price");
        $product->stock = $request->input("stock");
        $product->description = $request->input("description");

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $product->image = 'storage/images/' . $imageName;
        }
    
        $product->save();

        return redirect()->route('products.showDetail', ['id' => $id])->with('success', 'Product updated successfully');
    }

}
