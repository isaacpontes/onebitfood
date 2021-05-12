<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error getting categories."
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
            'title' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        try {
            $path = $request->file('image')->store('images/categories', 'public');
            $category = new Category();
            $category->title = $request->title;
            $category->image_url = $path;
            $category->save();
            return response()->json($category, 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error saving category."
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $category->update(['title' => $request->title]);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            Storage::disk('public')->delete($category->image_url);
            $category->delete();
            return response()->json([
                'message' => 'Category deleted'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error deleting category."
            ], 400);
        }
    }
}
