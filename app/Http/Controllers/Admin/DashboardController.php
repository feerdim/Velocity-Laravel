<?php

namespace App\Http\Controllers\Admin;

use App\Models\TopupCharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\GameMaster;
use App\Models\PlayerSchedule;
use App\Models\VsOneSchedule;
use App\Models\Type;
use App\Models\Activation;

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $player = VsOneSchedule::get();
        // $player = GameMaster::get();
        dd($player);
        $total_transaction = TopupCharge::sum('grand_total');
        $total_player = Player::get()->count();
        $total_competition = PlayerSchedule::get()->count();
        $total_game = Game::get()->count();
        return view('admin.dashboard.index', compact('total_transaction','total_player','total_competition','total_game'));
    }
}
