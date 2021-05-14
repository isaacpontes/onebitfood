<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'product_category_id' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        try {
            $path = $request->file('image')->store('images/products', 'public');
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = intval($request->price * 100);
            $product->image_url = $path;
            $product->product_category_id = $request->product_category_id;
            $product->save();
            return response()->json($product, 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error saving product.",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'product_category_id' => 'required',
        ]);

        try {
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => intval($request->price * 100),
                'product_category_id' => $request->product_category_id
            ]);
            return response()->json($product, 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error updating product.",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            Storage::disk('public')->delete($product->image_url);
            $product->delete();
            return response()->json([
                'message' => 'Product deleted'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error deleting product.",
                "error" => $th->getMessage()
            ], 400);
        }
    }
}
