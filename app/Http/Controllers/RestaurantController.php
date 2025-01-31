<?php

namespace App\Http\Controllers;

use MongoDB\Client;
use Illuminate\Http\JsonResponse;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/restaurants",
     *     summary="Get all restaurants for a specific city",
     *     description="Retrieve a list of restaurants filtered by city.",
     *     tags={"Restaurants"},
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="The city to filter restaurants by",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of restaurants",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string", description="Restaurant ID"),
     *                 @OA\Property(property="name", type="string", description="Restaurant name"),
     *                 @OA\Property(property="address", type="string", description="Restaurant address"),
     *                 @OA\Property(property="city", type="string", description="City of the restaurant"),
     *                 @OA\Property(property="image", type="string", description="Image URL"),
     *                 @OA\Property(property="ranking", type="integer", description="Restaurant ranking"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="City is required",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="City is required")
     *         )
     *     )
     * )
     */


    // Pobiera liste miast
    public function getAllCities(): JsonResponse
    {
        $client = new Client(env('MONGO_DSN', 'mongodb://127.0.0.1:27017'));
        $collection = $client->selectDatabase(env('DB_DATABASE', 'your_database'))->selectCollection('restaurants');
        $cities = $collection->distinct('city');
        return response()->json(array_values(array_filter($cities)));
    }




    public function getRestaurantsByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        $city = $request->query('city');

        if (!$city) {
            return response()->json(['error' => 'City is required'], 400);
        }

        $restaurants = Restaurant::query()->where('city', $city)
            ->select('id', 'name', 'address', 'city', 'image', 'ranking', 'created_at', 'updated_at')
            ->get();

        return response()->json($restaurants);
    }

    /**
     * @OA\Get(
     *     path="/restaurants/{id}/menu",
     *     summary="Get menu for a specific restaurant",
     *     description="Retrieve the menu of a specific restaurant by its ID.",
     *     tags={"Restaurants"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the restaurant",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant menu",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="Restaurant name"),
     *             @OA\Property(
     *                 property="menu",
     *                 type="array",
     *                 description="Menu items",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="title", type="string", description="Category title"),
     *                     @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         description="Items in the category",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="name", type="string", description="Item name"),
     *                             @OA\Property(property="price", type="string", description="Item price"),
     *                             @OA\Property(property="photoUrl", type="string", description="Item photo URL")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Restaurant not found")
     *         )
     *     )
     * )
     */



    public function getMenuByRestaurantId($id): \Illuminate\Http\JsonResponse
    {
        // Find the restaurant by MongoDB ObjectId
        $restaurant = Restaurant::query()->find($id);

        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        return response()->json([
            'name' => $restaurant->name,
            'menu' => $restaurant->menu,
        ]);
    }
}
