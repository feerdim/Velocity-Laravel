<?php

namespace App\Http\Controllers\Api;

use App\Models\Activation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameMaster;
use App\Models\Player;
use App\Models\User;
use App\Models\Type;
use App\Models\PlayerSchedule;
use App\Models\VsOneSchedule;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use phpDocumentor\Reflection\PseudoTypes\True_;

class ActivationController extends Controller
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
     * get user activation game
     *
     * @return void
     */
    public function gameActivation($id)
    {
        $activation = Activation::where('player_id', auth()->user()->id)
                            ->where('game_id', $id)
                            ->with('game')
                            ->first();
        return response()->json([
            'success' => true,
            'activation'    => $activation
        ], 200);
    }

    public function loading($id, $category, $slug)
    {
        $type = Type::find($slug);
        if($type->name == '1 VS 1'){
            $loading = VsOneSchedule::where('player_id', auth()->user()->id)
                                    ->where('game_id', $id)
                                    ->where('type_id', $slug)
                                    ->where('is_solo', $category)
                                    ->where(function($model){
                                        $model->where('game_status', 'pending')
                                        ->orWhere('game_status', 'playing');
                                    })
                                    ->with('type')
                                    ->first();
            if($loading){
                $enemy = VsOneSchedule::where('game_master_id', $loading->game_master_id)
                                        ->get()
                                        ->reject(function ($player){
                                            return $player->player_id == auth()->user()->id;
                                        })->first();
                if($enemy!=NULL){
                    $player = Player::find($enemy->player_id);
                    $activation = Activation::where('player_id', $enemy->player_id)->where('game_id', 1)->first();
                    $loading->account_id = $activation->account_id;
                    $loading->account_name = $activation->account_name;
                    $loading->avatar = $player->avatar;
                }
            }
        }
        else{
            $loading = PlayerSchedule::where('player_id', auth()->user()->id)
                                    ->where('game_id', $id)
                                    ->where('type_id', $slug)
                                    ->where('is_solo', $category)
                                    ->where('game_status', 'pending')
                                    ->orWhere('game_status', 'playing')
                                    ->with('type')
                                    ->first();
        }
        return response()->json([
            'success' => true,
            'loading' => $loading
        ]);
    }

    /**
     * create user activation game
     *
     * @return void
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'account_id'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $activation = Activation::create([
            'account_id' => $request->account_id,
            'account_name' => $request->account_name,
            'status' => false,
            'player_id' => auth()->user()->id,
            'game_id' => $id,
        ]);
        if($activation) {
            $this->broadcastMessage(auth()->user()->name.' activate new game account','Please check and validate the data');
            return response()->json([
                'success' => true,
                'activation'    => $activation
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'internal server error'
            ], 500);
        }
    }

    private function broadcastMessage($title, $message)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            ->setClickAction(env('APP_URL').'/admin/player');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'message' => $message
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = User::all()->pluck('fcm_token')->toArray();
        
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        
        return $downstreamResponse->numberSuccess();
    }

    /**
     * update user activation game
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'account_id'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $activation = Activation::findOrFail($id);
        $activation->update([
            'account_id' => $request->account_id,
            'account_name' => $request->account_name
        ]);
        
        if($activation) {
            return response()->json([
                'success' => true,
                'activation'    => $activation
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'internal server error'
            ], 500);
        }
    }
}
