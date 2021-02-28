<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    } 

    /**
     * update avatar and name
     *
     * @return void
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'avatar'=> 'image|mimes:jpeg,jpg,png|max:3000',
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if ($request->file('avatar') == "" ) {
            Player::where('id',auth()->user()->id)->update([
                'name' => $request->name
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Update data success'
            ], 200);
        }

        $image_name = basename(auth()->user()->avatar).PHP_EOL;
        Storage::disk('local')->delete('public/players/'. $image_name);
        $image = $request->file('avatar');
        $image->storeAs('public/players', $image->hashName());
        Player::where('id',auth()->user()->id)->update([
            'avatar' => $image->hashName(),
            'name' => $request->name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Update data success'
        ], 200);
    }

    /**
     * update password
     *
     * @return void
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        Player::where('id',auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'success' => true,
            'message'    => 'Password has been reset'
        ], 200);
    }
}
