<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/orders",
     *     operationId="createOrder",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     description="Create an order for a restaurant",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="session_id", type="string", example="user-session-12345"),
     *             @OA\Property(property="restaurant_id", type="string", example="677993288fbedfb0b45059fd"),
     *             @OA\Property(property="items", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="menu_item_id", type="string", example="Kanapka Norweska"),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=404, description="Restaurant not found"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'restaurant_id' => 'required|string|exists:restaurants,_id',
            'items' => 'required|array'
        ]);

        $restaurant = Restaurant::find($validated['restaurant_id']);
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        $totalPrice = 0;
        foreach ($validated['items'] as $item) {
            $menuItem = collect($restaurant->menu)
                ->flatMap(fn($section) => $section['items'])
                ->firstWhere('name', $item['menu_item_id']);

            if (!$menuItem) {
                return response()->json(['error' => "Menu item '{$item['menu_item_id']}' not found"], 404);
            }

            $price = (float)str_replace(',', '.', $menuItem['price']);
            $totalPrice += $price * $item['quantity'];
        }

        $order = Order::query()->create([
            'session_id' => $validated['session_id'],
            'restaurant_id' => $validated['restaurant_id'],
            'items' => $validated['items'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json($order, 201);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     operationId="getOrderDetails",
     *     tags={"Orders"},
     *     summary="Get order details",
     *     description="Fetch details of an existing order by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Order ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    /**
     * @OA\Put(
     *     path="/orders/{id}",
     *     operationId="updateOrderStatus",
     *     tags={"Orders"},
     *     summary="Update order status",
     *     description="Update the status of an existing order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Order ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="confirmed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order status updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=404, description="Order not found"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     */

    public function updateStatus(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,completed,cancelled'
        ]);

        $order = Order::query()->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update([
            'status' => $validated['status'],
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Order status updated successfully']);
    }

    /**
     * @OA\Delete(
     *     path="/orders/{id}",
     *     operationId="cancelOrder",
     *     tags={"Orders"},
     *     summary="Cancel an order",
     *     description="Cancel an existing order by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Order ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order cancelled successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order cancelled successfully']);
    }
}
