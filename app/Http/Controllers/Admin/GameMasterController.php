<?php

namespace App\Http\Controllers\Admin;


use App\Models\GameMaster;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerSchedule;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class GameMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        /**
         * algorithm create randomstring
         */
        $length = 10;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }

        $randomString = Str::upper($random);
        $hash = hash('crc32b', $randomString);
        $t=time();
        $timeStamp = date("Y_m_d",$t);
        
        $room_id =  $timeStamp . $hash;

        $batches = PlayerSchedule::whereIn('id', array_keys($request->all()))->get();;
        // dd($batches[0]->is_solo);
        
        $game_master = GameMaster::create([
            'room_id' => $room_id,
            'user_id' => auth()->user()->id
        ]);
        
        // dd($all_player_with_same_schedule);
        if($game_master) {
            PlayerSchedule::whereIn('id', array_keys($request->all()))
            ->update([ 'game_master_id' => $game_master->id, 'game_status' => 'playing']);

            $this->broadcastMessage('Game has started','Check your game in the profile',array_keys($request->all()));
            if($batches[0]->is_solo) {
                return redirect()->route('admin.solo_schedule.index')->with(['success' => 'Ayo Handle Game lainnya !']);
            } else {
                return redirect()->route('admin.team_schedule.index')->with(['success' => 'Ayo Handle Game lainnya !']);
            }
        } else {
            return redirect()->route('admin.solo_schedule.index')->with(['error' => 'Gagal membuat game master !']);
        }


    }

    private function broadcastMessage($title, $message, $batches)
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
        // $tokens = Player::whereIn('id', array_keys($request->all()))->get();;
        $tokens = Player::with(['player_schedules' => function($query) {
            global $batches;
            $query->whereIn('id', $batches);
        }])->pluck('fcm_token')->toArray();
        
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        
        return $downstreamResponse->numberSuccess();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
