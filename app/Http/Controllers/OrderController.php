<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    /**
     * List all orders (Admin)
     */
    public function index()
    {
        $orders = Order::with(['user', 'product'])->get();
        return response()->json($orders, 200);
    }

    /**
     * Show single order detail (Admin)
     */
    public function show($id)
    {
        $order = Order::with(['user', 'product'])->find($id);
        if(!$order){
            return response()->json(['message' => 'Order not found.'], 404);
        }
        return response()->json($order, 200);
    }

    /**
     * Create a new order (Verified Users)
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|uuid|exists:products,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Get authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        // Get product
        $product = $user->orders()->where('product_id', $request->product_id)->first();
        if(!$product){
            return response()->json(['message' => 'Product not found.'], 404);
        }

        // Calculate total price
        $totalPrice = $product->price * $request->quantity;

        // Create order
        $order = Order::create([
            'product_id' => $request->product_id,
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Order created successfully.', 'order' => $order], 201);
    }

    /**
     * Update order status (Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if(!$order){
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,success,cancel',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Update status
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully.', 'order' => $order], 200);
    }

    /**
     * Delete an order (Admin)
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if(!$order){
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully.'], 200);
    }
}
