<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
            'name' => 'required|string',
            'phone' => 'required|string',
            'total_value' => 'required|numeric',
            'street' => 'required|string',
            'number' => 'required|string',
            'complement' => 'required|string',
            'neighborhood' => 'required|string',
            'city' => 'required|string',
            'restaurant_id' => 'required|string'
        ]);

        try {
            $order = new Order();
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->total_value = intval($request->total_value * 100);
            $order->street = $request->street;
            $order->number = $request->number;
            $order->complement = $request->complement;
            $order->neighborhood = $request->neighborhood;
            $order->city = $request->city;
            $order->restaurant_id = $request->restaurant_id;

            DB::transaction(function () use ($request, $order) {
                $order->save();
                $order->products()->attach($request->products);
            });

            return response()->json($order, 201);
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load('products');
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json([
                'message' => 'Order deleted'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error deleting order.",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Update the status of the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        try {
            $order->update([
                'status' => $request->status
            ]);

            return response('', 204);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error updating order status.",
                "error" => $th->getMessage()
            ], 400);
        }
    }
}
