<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerSchedule;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $games = Game::latest()->get();
        return response()->json([
            'success'   => true,
            'games'  => $games
        ], 200);
    }

    /**
     * solo game
     *
     * @return void
     */
    public function showSolo($slug)
    {
        $games = Game::where('slug', $slug)->with(['solo' => function($query) {
            $query->with(['types' => function($query) {
                $query->with(['awards' => function($query) {
                    $query->orderBy('nominal', 'desc');
                }]);
            }]);
        }])->first();
        return response()->json([
            'success'   => true,
            'solo_games'  => $games
        ], 200);
    }

    /**
     * team game
     *
     * @return void
     */
    public function showTeam($slug)
    {
        $games = Game::where('slug', $slug)->with(['team' => function($query) {
            $query->with(['types' => function($query) {
                $query->with(['awards' => function($query) {
                    $query->orderBy('nominal', 'desc');
                }]);
            }]);
        }])->first();
        return response()->json([
            'success'   => true,
            'team_games'  => $games
        ], 200);
    }
}
