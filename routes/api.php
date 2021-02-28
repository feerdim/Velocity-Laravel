<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\VsOneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * PUSH NOTIFICATION
 */
Route::post('/savetoken/user', [FcmController::class, 'setUserToken'])->name('api.user.setUserToken');
Route::post('/savetoken/player', [FcmController::class, 'setPlayerToken'])->name('api.user.setPlayerToken');

/**
 * Route API Auth
 */
Route::post('/login', [AuthController::class, 'login'])->name('api.player.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.player.register');
Route::post('/googleLogin', [AuthController::class, 'googleLogin'])->name('api.player.google');
Route::post('/steamLogin', [AuthController::class, 'steamLogin'])->name('api.player.steam');
Route::get('/profile', [AuthController::class, 'getProfile'])->name('api.player.profile');
Route::post('/profile', [ProfileController::class, 'update'])->name('api.player.update');
Route::post('/profile/resetpassword', [ProfileController::class, 'reset'])->name('api.player.resetpassword');

/**
 * Route API Games
 */
Route::prefix('game')->group(function () {
    Route::get('/', [GameController::class, 'index'])->name('player.product.index');
    Route::get('/solo/{slug?}', [GameController::class, 'showSolo'])->name('player.solo.show');
    Route::get('/team/{slug?}', [GameController::class, 'showTeam'])->name('player.team.show');
    // id = game id
    Route::get('/loading/{id?}/{category?}/{slug?}', [ActivationController::class, 'loading'])->name('player.loading');
    Route::get('/activation/{id?}', [ActivationController::class, 'gameActivation'])->name('player.activation.show');

    Route::post('/vsOne', [VsOneController::class, 'update'])->name('player.vsOne.update');
});

/**
 * Route API booking
 */
Route::prefix('booking')->group(function () {
    Route::get('/time', [BookingController::class, 'getTime'])->name('player.booking.time');
    Route::post('/', [BookingController::class, 'booking'])->name('player.booking.store');
    // Route::get('/solo/{slug?}', [GameController::class, 'showSolo'])->name('player.solo.show');
    // Route::get('/team/{slug?}', [GameController::class, 'showTeam'])->name('player.team.show');
    // id = game id
    // Route::get('/activation/{id?}', [ActivationController::class, 'gameActivation'])->name('player.activation.show');
});

/**
 * Route API Activation
 */
Route::prefix('activation')->group(function () {
    // id = game id
    Route::post('/game/{id?}', [ActivationController::class, 'store'])->name('player.activation.store');
    Route::put('/{id?}/game/{game_id?}', [ActivationController::class, 'update'])->name('player.activation.update');
});

/**
 * Route API Crown Packages
 */
Route::prefix('crown')->group(function () {
    // id = game id
    Route::get('/', [CrownPackageController::class, 'index'])->name('player.crown.index');
});

/**
 * Route TOP UP
 */
Route::post('/topup', [TopupController::class, 'store'])->name('player.topup.store');
Route::get('/transaction', [TopupController::class, 'index'])->name('player.topup.index');
Route::post('/notificationHandler', [TopupController::class, 'notificationHandler'])->name('player.topup.notificationHandler');

Route::get('/leaderboard/{id?}/{slug?}', [LeaderBoardController::class, 'show'])->name('player.leaderboard.show');