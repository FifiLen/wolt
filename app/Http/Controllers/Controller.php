<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Wolt API",
 *     version="1.0.0",
 *     description="API documentation for the Wolt-like application",
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API server"
 * )
 *
 * @OA\Components(
 *     schemas={
 *         @OA\Schema(
 *             schema="Order",
 *             type="object",
 *             properties={
 *                 @OA\Property(property="id", type="string", description="Order ID"),
 *                 @OA\Property(property="session_id", type="string", description="Session ID"),
 *                 @OA\Property(property="restaurant_id", type="string", description="Restaurant ID"),
 *                 @OA\Property(property="items", type="array", description="Ordered items",
 *                     @OA\Items(
 *                         type="object",
 *                         properties={
 *                             @OA\Property(property="menu_item_id", type="string", description="Menu item ID"),
 *                             @OA\Property(property="quantity", type="integer", description="Quantity ordered")
 *                         }
 *                     )
 *                 ),
 *                 @OA\Property(property="total_price", type="number", format="float", description="Total price of the order"),
 *                 @OA\Property(property="status", type="string", description="Order status", enum={"pending", "confirmed", "completed", "cancelled"}),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Order creation time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Order last update time")
 *             }
 *         )
 *     }
 * )
 */
abstract class Controller
{
    //
}
