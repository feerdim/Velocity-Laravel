<?php

use App\Http\Controllers\Admin\CompetitionController;
use App\Http\Controllers\Admin\CrownPackageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\GameMasterController;
use App\Http\Controllers\Admin\HistoryScheduleController;
use App\Http\Controllers\Admin\MmrController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SoloScheduleController;
use App\Http\Controllers\Admin\TeamScheduleController;
use App\Http\Controllers\Admin\TimeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VsOneController;
use App\Models\VsOneSchedule;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // dd('coba');
    return view('auth.login');
});

/**
 * route for admin
 */

//group route with prefix "admin"
Route::prefix('admin')->group(function () {

    //group route with middleware "auth"
    Route::group(['middleware' => 'auth'], function() {
        //profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::post('/profile', [ProfileController::class, 'updatePhoto'])->name('admin.profile.update');

        //route dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

        //permissions
        Route::resource('/permission', PermissionController::class, ['except' => ['show', 'create', 'edit', 'update', 'delete'] ,'as' => 'admin']);

        //roles
        Route::resource('/role', RoleController::class, ['except' => ['show'] ,'as' => 'admin']);

        //users
        Route::resource('/user', UserController::class, ['except' => ['show'] ,'as' => 'admin']);

        //games
        Route::resource('/game', GameController::class, ['except' => ['show'] ,'as' => 'admin']);

        //game solo
        Route::get('/game/{game}/solo', [GameController::class, 'showSolo'])->name('admin.game.solo');

        //game award
        Route::get('/game/{game}/type/{type}/award', [GameController::class, 'showAward'])->name('admin.award.show');
        Route::post('/game/{game}/type/{type}/award', [GameController::class, 'storeAward'])->name('admin.award.store');
        Route::delete('/game/{game}/type/{type}/award/{id}', [GameController::class, 'deleteAward'])->name('admin.award.destroy');

        // types
        Route::get('/type', [TypeController::class, 'index'])->name('admin.type.index');
        Route::get('/type/create', [TypeController::class, 'create'])->name('admin.type.create');
        Route::post('/type', [TypeController::class, 'store'])->name('admin.type.store');
        Route::get('/type/{type}/edit', [TypeController::class, 'edit'])->name('admin.type.edit');
        Route::put('/type/{type}', [TypeController::class, 'update'])->name('admin.type.update');

        //solo type
        Route::get('/game/{game}/solo/{solo}/type', [TypeController::class, 'createSolo'])->name('admin.solo.create');
        Route::post('/game/{game}/solo/{solo}/type', [TypeController::class, 'storeSolo'])->name('admin.solo.store');
        Route::post('/game/{game}/solo/{solo}/mass/type', [TypeController::class, 'storeMassSolo'])->name('admin.solo.storeMass');
        Route::delete('/game/destroy/solo', [TypeController::class, 'destroySolo'])->name('admin.solo.destroy');

        //game team
        Route::get('/game/{game}/team', [GameController::class, 'showTeam'])->name('admin.game.team');

        //team type
        Route::get('/game/{game}/team/{team}/type', [TypeController::class, 'createTeam'])->name('admin.team.create');
        Route::post('/game/{game}/team/{team}/type', [TypeController::class, 'storeTeam'])->name('admin.team.store');
        Route::post('/game/{game}/team/{team}/mass/type', [TypeController::class, 'storeMassTeam'])->name('admin.team.storeMass');
        Route::delete('/game/destroy/team', [TypeController::class, 'destroyTeam'])->name('admin.team.destroy');

        //player
        Route::resource('/player', PlayerController::class, ['as' => 'admin']);

        //player activation
        Route::get('/player/{player}/activation', [PlayerController::class, 'showActivation'])->name('admin.playerActivation.show');
        Route::put('/player/{player}/activation', [PlayerController::class, 'updateActivation'])->name('admin.playerActivation.update');

        //crown packages
        Route::resource('/crown_package', CrownPackageController::class, ['except' => ['show'], 'as' => 'admin']);
        //TRANSACTION packages
        Route::resource('/transaction', TransactionController::class, ['except' => ['show'], 'as' => 'admin']);

        //mmr packages
        Route::resource('/mmr', MmrController::class, ['except' => ['show'], 'as' => 'admin']);

        //time
        Route::resource('/time', TimeController::class, ['except' => ['show'], 'as' => 'admin']);
        
        //solo schedule
        Route::resource('/solo_schedule', SoloScheduleController::class, ['except' => ['show','create','store','edit','update','store'], 'as' => 'admin']);
        Route::get('/solo_schedule/{solo_schedule}/game/{game}/type/{type}', [SoloScheduleController::class, 'showDetail'])->name('admin.solo_schedule.showDetail');

        //team schedule
        Route::resource('/team_schedule', TeamScheduleController::class, ['except' => ['show','create','store','edit','update','store'], 'as' => 'admin']);
        Route::get('/team_schedule/{team_schedule}/game/{game}/type/{type}', [TeamScheduleController::class, 'showDetail'])->name('admin.team_schedule.showDetail');

        //one vs one schedule
        Route::get('/vs_one_schedule', [VsOneController::class, 'index'])->name('admin.vs_one_schedule.index');
        Route::post('/vs_one_schedule', [VsOneController::class, 'store'])->name('admin.vs_one_schedule.store');
        Route::get('/vs_one_schedule/refund/{id}', [VsOneController::class, 'refund'])->name('admin.vs_one_schedule.refund');

        Route::get('/vs_one', [VsOneController::class, 'historyIndex'])->name('admin.vs_one.index');
        Route::post('/vs_one/winner/{game_master}', [VsOneController::class, 'winner'])->name('admin.vs_one.winner');
        Route::get('/vs_one/{game_master}/type/{type}', [VsOneController::class, 'historyShow'])->name('admin.vs_one.show');

        //history schedule
        Route::resource('/history_schedule', HistoryScheduleController::class, ['except' => ['show','create','store','edit','update','store','destroy'], 'as' => 'admin']);


        // Route::resource('/vs_one', HistoryScheduleController::class, ['except' => ['show','create','store','edit','update','store','destroy'], 'as' => 'admin']);
        Route::get('/pending/history_schedule', [HistoryScheduleController::class, 'allPending'])->name('admin.history_schedule.pending');
        Route::get('/all/history_schedule', [HistoryScheduleController::class, 'allHistory'])->name('admin.history_schedule.all');
        Route::get('/history_schedule/{player_schedule}/game/{game}/type/{type}', [HistoryScheduleController::class, 'show'])->name('admin.history_schedule.show');
        Route::post('/history_schedule/winner/{player_schedule}', [HistoryScheduleController::class, 'winner'])->name('admin.history_schedule.winner');
        Route::post('/history_schedule/set_win_count/{player_schedule}/player/{player}', [HistoryScheduleController::class, 'setWinCount'])->name('admin.history_schedule.setwin');
        Route::post('/history_schedule/set_lose_count/{player_schedule}/player/{player}', [HistoryScheduleController::class, 'setLoseCount'])->name('admin.history_schedule.setlose');
        Route::get('/history_schedule/refund/{player_schedule}', [HistoryScheduleController::class, 'refund'])->name('admin.history_schedule.refund');
        // Route::get('/team_schedule/{team_schedule}/game/{game}/type/{type}', [TeamScheduleController::class, 'showDetail'])->name('admin.team_schedule.showDetail');
        
        //game master
        // Route::resource('/game_master', GameMasterController::class, ['except' => ['show'], 'as' => 'admin']);
        Route::get('/game_master/schedule', [GameMasterController::class, 'create'])->name('admin.game_master.create');
    });
});