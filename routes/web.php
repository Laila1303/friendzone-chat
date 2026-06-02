<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [ChatController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/conversations/add', [ChatController::class, 'addContact'])->name('conversations.add');
    Route::post('/messages/send', [ChatController::class, 'sendMessage'])->name('messages.send');
    Route::post('/groups/create', [ChatController::class, 'createGroup'])->name('groups.create');
    
    // ========================================================
    // FIX TOTAL: SISTEM ONLINE MURNI LEWAT DATABASE (SINKRON HP & LAPTOP)
    // ========================================================
    Route::get('/user/{id}/ping-online', function($id) {
        // Update waktu aktif user langsung ke database
        User::where('id', $id)->update(['updated_at' => now()]);
        return response()->json(['status' => 'success']);
    });

    Route::get('/user/{id}/status-check', function($id) {
        $user = User::find($id);
        // Jika updated_at user kurang dari 40 detik yang lalu, dianggap sedang membuka chat
        $isOnline = $user && $user->updated_at->diffInSeconds(now()) < 40;
        return response()->json(['is_online' => $isOnline]);
    });
    // ========================================================

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';