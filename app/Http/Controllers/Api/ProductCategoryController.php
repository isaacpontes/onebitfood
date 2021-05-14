<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $productCategories = ProductCategory::query()
                ->with('products')
                ->get();
            return response()->json($productCategories);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error getting product categories.",
                "error" => $th->getMessage()
            ], 400);
        }
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
            'title' => 'required|string',
            'restaurant_id' => 'required|numeric'
        ]);

        try {
            $productCategory = new ProductCategory();
            $productCategory->title = $request->title;
            $productCategory->restaurant_id = $request->restaurant_id;
            $productCategory->save();
            return response()->json($productCategory, 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error saving product category.",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $productCategory)
    {
        $productCategory->load('products');
        return response()->json($productCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        try {
            $productCategory->update(['title' => $request->title]);
            return response()->json($productCategory, 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error updating product category.",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
            return response()->json([
                'message' => 'Product category deleted'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error deleting Product category.",
                "error" => $th->getMessage()
            ], 400);
        }
    }
}
