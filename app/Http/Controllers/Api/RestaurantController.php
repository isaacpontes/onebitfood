<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table("restaurants");

            if ($request->query("s")) {
                $search = $request->query("s");
                $query->where("name", "like", "%{$search}%");
                // ->where("description", "like", "%{$search}%", "or");
            }

            if ($request->query("city")) {
                $city = $request->query("city");
                $query->where("city", $city);
            }

            if ($request->query("category")) {
                $category_id = $request->query("category");
                $query->where("category_id", $category_id);
            }

            $query->rightJoin('categories', 'restaurants.category_id', '=', 'categories.id');

            $restaurants = $query->get([
                "restaurants.id",
                "restaurants.name",
                "restaurants.description",
                "restaurants.delivery_tax",
                "restaurants.street",
                "restaurants.number",
                "restaurants.complement",
                "restaurants.neighborhood",
                "restaurants.city",
                "restaurants.image_url",
                "categories.title AS category"
            ]);

            return response()->json($restaurants);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error getting restaurants.",
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
            "name" => "required",
            "description" => "required",
            "delivery_tax" => "required|numeric",
            "street" => "required",
            "number" => "required",
            "complement" => "required",
            "neighborhood" => "required",
            "city" => "required",
            "category_id" => "required",
            "image" => "required|mimes:png,jpg,jpeg|max:2048"
        ]);

        try {
            $path = $request->file("image")->store("images/restaurants", "public");
            $restaurant = new Restaurant();
            $restaurant->name = $request->name;
            $restaurant->description = $request->description;
            $restaurant->delivery_tax = intval($request->delivery_tax * 100);
            $restaurant->street = $request->street;
            $restaurant->number = $request->number;
            $restaurant->complement = $request->complement;
            $restaurant->neighborhood = $request->neighborhood;
            $restaurant->city = $request->city;
            $restaurant->image_url = $path;
            $restaurant->category_id = $request->category_id;
            $restaurant->save();
            return response()->json($restaurant, 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error saving restaurant.",
                "error" => $th
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load("product_categories");
        return response()->json($restaurant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        try {
            Storage::disk("public")->delete($restaurant->image_url);
            $restaurant->delete();
            return response()->json([
                "message" => "Restaurant deleted"
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error deleting restaurant."
            ], 400);
        }
    }
}
