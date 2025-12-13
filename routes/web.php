<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatPageController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ConnectionController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    // return view('welcome');
    // return redirect()->route('dashboard'); // or home page
    return auth()->check() ? redirect('/dashboard') : view('welcome');
});

// Route::get('/', function () {
//     return redirect('/index.html');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/chat/{user}', [ChatPageController::class, 'show'])->middleware('auth');

Route::get('/chat', [ChatPageController::class, 'index'])->middleware('auth');

// Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('upload.image');

Route::middleware('auth')->delete('/web/connections/{id}', [ConnectionController::class, 'removeConnection']);
Route::middleware('auth')->get('/web/connections', [ConnectionController::class, 'indexWeb']);
Route::middleware('auth')->post('/web/connections/{id}/remove-sidebar', [ConnectionController::class, 'removeFromSidebar']);
// Route::delete('/connections/{id}/remove-sidebar', [ConnectionController::class, 'removeFromSidebar'])->name('connections.removeSidebar');

// Profile photo upload
Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])
    ->middleware('auth')
    ->name('profile.photo');
    
//Dashboard logs
Route::get('/logs/recent', function () {
    $file = storage_path('logs/laravel.log');
    $lines = [];

    if (file_exists($file)) {
        $lines = array_slice(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -10);
    }

    return response()->json($lines);
});

