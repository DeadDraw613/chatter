<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ConnectionController;

Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    // quick check route (temporary) to verify Sanctum session auth
    Route::get('/check-auth', function (Request $request) {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user(),
        ]);
    });

    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/messages/{user}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
});

Route::post('/messages/image-upload', [\App\Http\Controllers\ImageController::class, 'upload'])
    ->middleware('auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/connections', [ConnectionController::class, 'index']);
    Route::post('/connections/request', [ConnectionController::class, 'requestConnection']);
    Route::post('/connections/{id}/respond', [ConnectionController::class, 'respondToRequest']);
    Route::delete('/connections/{id}', [ConnectionController::class, 'removeConnection']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->delete('/connections/{id}', [ConnectionController::class, 'removeConnection']);

// routes/api.php
// Route::delete('/connections/{id}', [ConnectionController::class, 'removeConnection'])
//     ->middleware('auth:sanctum');


// Route::post('/login', [ApiAuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
// Route::middleware('auth')->group(function () {
//     Route::get('/messages/{user}', [MessageController::class, 'index']);
//     Route::post('/messages', [MessageController::class, 'store']);
//     Route::post('/logout', [ApiAuthController::class, 'logout']);
// });


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are prefixed with /api automatically. They are protected
| by the sanctum middleware so your SPA (with credentials: 'include')
| can authenticate via the session cookie.
|
*/




// Route::get('/check-auth', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/check-auth', function (Request $request) {
//     return response()->json([
//         'user' => $request->user(),
//     ]);
// });

?>