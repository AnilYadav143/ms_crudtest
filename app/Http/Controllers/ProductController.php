<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image_name = 'product' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $image_name);
            $path_img = 'images/' . $image_name;
        }

        $product = Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'image' => $path_img ?? null,
        ]);

        Alert::success('Success', 'Product created successfully!');
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image_name = 'product' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $image_name);
            $product->image = 'images/' . $image_name;
        }

        $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'image' => $product->image,
        ]);

        Alert::success('Success', 'Product updated successfully!');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        $product->delete();

        Alert::success('Success', 'Product deleted successfully!');
        return redirect()->route('products.index');
    }



}
