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
 *         ),
 *         @OA\Schema(
 *             schema="User",
 *             type="object",
 *             properties={
 *                 @OA\Property(property="id", type="string", description="User ID"),
 *                 @OA\Property(property="name", type="string", description="User's full name"),
 *                 @OA\Property(property="email", type="string", description="User's email address"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Account creation time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last account update time")
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="Restaurant",
 *             type="object",
 *             properties={
 *                 @OA\Property(property="id", type="string", description="Restaurant ID"),
 *                 @OA\Property(property="name", type="string", description="Restaurant name"),
 *                 @OA\Property(property="address", type="string", description="Restaurant address"),
 *                 @OA\Property(property="city", type="string", description="City where the restaurant is located"),
 *                 @OA\Property(property="image", type="string", format="url", description="Restaurant image URL"),
 *                 @OA\Property(property="ranking", type="integer", description="Restaurant ranking"),
 *                 @OA\Property(property="menu", type="array", description="Restaurant menu sections",
 *                     @OA\Items(ref="#/components/schemas/Menu")
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Restaurant creation time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Restaurant last update time")
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="Menu",
 *             type="object",
 *             properties={
 *                 @OA\Property(property="title", type="string", description="Menu section title"),
 *                 @OA\Property(property="items", type="array", description="List of menu items",
 *                     @OA\Items(ref="#/components/schemas/MenuItem")
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="MenuItem",
 *             type="object",
 *             properties={
 *                 @OA\Property(property="name", type="string", description="Menu item name"),
 *                 @OA\Property(property="price", type="string", description="Menu item price"),
 *                 @OA\Property(property="photoUrl", type="string", format="url", description="Menu item image URL")
 *             }
 *         )
 *     }
 * )
 */
abstract class Controller
{
    //
}
