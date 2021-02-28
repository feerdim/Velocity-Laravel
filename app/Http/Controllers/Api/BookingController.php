<?php

namespace App\Http\Controllers\Api;

use FCM;
use App\Models\Type;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\PlayerSchedule;
use App\Http\Controllers\Controller;
use App\Models\VsOneSchedule;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class BookingController extends Controller
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
     * get booking time
     *
     * @return void
     */
    public function getTime()
    {
        $schedules = Schedule::orderBy('start','asc')->get();
        return response()->json([
            'success' => true,
            'schedules'    => $schedules
        ], 200);
    }

    /**
     * book a game
     *
     * @return void
     */
    public function booking(Request $request)
    {
        $type = Type::where('id', $request->type_id)->first();
        $schedule = Schedule::where('id',$request->schedule_id)
        ->with(['player_schedules' => function($query) {
            global $request;
            $query->where('is_solo', $request->is_solo)
            ->where('game_status', 'pending')
            ->where('game_id', $request->game_id);
        }])->first();
        // cek saldo cukup atau tidak
        if(auth()->user()->crown >= $type->crown_price) {
            if($type->name == '1 VS 1') {
                $success = VsOneSchedule::create([
                    'player_id' => auth()->user()->id,
                    'game_id' => $request->game_id,
                    'type_id' => $request->type_id,
                    'is_solo' => $request->is_solo,
                    'game_status' => 'pending'
                ]);
                if($success) {
                    auth()->user()->crown = auth()->user()->crown -  $type->crown_price;
                    auth()->user()->save();
                    $this->broadcastMessage('New Game was booked','Please check and start the game');
                    return response()->json([
                        'success' => true,
                        'player_schedule'    => $success
                    ], 200);    
                } else {
                    return response()->json([
                        'success' => false,
                        'message'    => 'internal server error'
                    ], 500);    
                }
            } else {
                $success = PlayerSchedule::create([
                    'player_id' => auth()->user()->id,
                    'schedule_id' => $request->schedule_id,
                    'game_id' => $request->game_id,
                    'type_id' => $request->type_id,
                    'is_solo' => $request->is_solo,
                    'game_status' => 'pending'
                ]);
                if($success) {
                    auth()->user()->crown = auth()->user()->crown -  $type->crown_price;
                    auth()->user()->save();
                    $this->broadcastMessage('New Game was booked','Please check and start the game');
                    return response()->json([
                        'success' => true,
                        'player_schedule'    => $success
                    ], 200);    
                } else {
                    return response()->json([
                        'success' => false,
                        'message'    => 'internal server error'
                    ], 500);    
                }
            }
            
        } else {
            return response()->json([
                'success' => false,
                'message'    => 'crown tidak cukup, silahkan top up'
            ], 400);    
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
}
