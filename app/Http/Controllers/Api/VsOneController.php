<?php

namespace App\Http\Controllers\Api;

use App\Models\VsOneSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VsOneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 

    public function update(Request $request)
    {
        // return response()->json([
        //     'balik' => $request
        // ]);
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:3000',
            'game_master' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // $date = date("/Y/m/");
        $image = $request->file('image');
        $image->storeAs('public/schedule/', $image->hashName());
        VsOneSchedule::where('player_id',auth()->user()->id)->where('game_master_id',$request->game_master)->update([
            'image' => $image->hashName(),
            'game_status' => 'waiting'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Update data success'
        ], 200);
    }
}
