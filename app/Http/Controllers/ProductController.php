<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index(Request $request) {
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
        ]); }

    public function showDetail($id) {
        $product = Product::find($id);
    
        return view('products.detail', ['product'=> $product]); }

    public function delete($id) {
        $product = Product::findOrFail($id);
        logger($product); 
    
        $product->delete();
        return redirect('/products'); }

}
