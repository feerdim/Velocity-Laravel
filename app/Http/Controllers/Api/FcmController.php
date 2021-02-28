<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['setUserToken']);
    } 

    public function setUserToken(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'retrieve user fcm token'
        ]);

    }

    public function setPlayerToken(Request $request)
    {
        $player = auth()->user();
        $player->fcm_token = $request->fcm_token;
        $player->save();
        return response()->json([
            'success' => true,
            'message' => 'retrieve player fcm token'
        ]);

    }
}
