<?php

use Illuminate\Support\Facades\Route;
use App\Models\Restaurant;

// ✅ Najpierw definicja endpointu testowego MongoDB
Route::get('/test-mongodb', function () {
    return Restaurant::limit(5)->get();
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
