<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Type;
use App\Models\Player;
use App\Models\Award;
use App\Models\Game;
use App\Models\GameMaster;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PlayerSchedule;
use App\Http\Controllers\Controller;
use App\Models\VsOneSchedule;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class VsOneController extends Controller
{
    private function broadcastMessage($title, $message, $vsOne)
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

        $tokens = Player::with(['vs_one_schedules' => function($query) {
            global $vsOne;
            $query->where('game_id','=',$vsOne->game_id)
            ->where('type_id','=',$vsOne->type_id)
            ->where('game_master_id','=',$vsOne->game_master_id)
            ->where('is_solo','=',$vsOne->is_solo);
        }])->pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        return $downstreamResponse->numberSuccess();
    }

    public function index()
    {
        $vs_one = Type::where('name', '1 VS 1')->first();
        if($vs_one) {
            $solos = VsOneSchedule::latest()->where('is_solo', true)
            ->where('game_status', 'pending')
            ->where('type_id', $vs_one->id)->get();
            $teams = VsOneSchedule::latest()->where('is_solo', false)
            ->where('game_status', 'pending')
            ->where('type_id', $vs_one->id)->get();
            return view('admin.vs_one_schedule.index', compact('solos', 'teams'));
        } else {
            abort(404);
            return view('admin.vs_one_schedule.index');            
        }
    }

    public function store(Request $request)
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
        
        // type one vs one
        $vs_one = Type::where('name','1 VS 1')->first();

        if(count($request->input('teams')) !== 2) {
            return redirect()->route('admin.vs_one_schedule.index')->with(['error' => 'Minimal 2 player !']);
        } else {
            $vs_one_schedules = VsOneSchedule::whereIn('id', $request->input('teams'))
            ->where('type_id', $vs_one->id)
            ->where('game_status', 'pending')
            ->get();
            // dd($vs_one_schedules);
            if($vs_one_schedules) {
                $game_master = GameMaster::create([
                    'room_id' => $room_id,
                    'user_id' => auth()->user()->id
                ]);
                foreach($vs_one_schedules as $vs_one_schedule){
                    if($vs_one_schedule->id == $request->game_master){
                        $is_game_master = true;
                    }
                    else{
                        $is_game_master = false;
                    }
                    $vs_one_schedule->update([
                        'game_master_id' => $game_master->id, 
                        'game_status' => 'playing',
                        'time' => Carbon::now()->addMinute(10),
                        'is_game_master' => $is_game_master,
                    ]);
                }
                // if($request->game_master == )
                // VsOneSchedule::whereIn('id', $request->input('teams'))
                // ->where('type_id', $vs_one->id)
                // ->where('game_status', 'pending')
                // ->update([ 
                //     'game_master_id' => $game_master->id, 
                //     'game_status' => 'playing',
                //     'time' => Carbon::now()->addMinute(10),
                // ]);
                return redirect()->route('admin.vs_one_schedule.index')->with(['success' => 'Ayo Handle Game lainnya !']);
            } 
            else {
                return redirect()->route('admin.vs_one_schedule.index')->with(['error' => 'Internal server error !']);
            }
        }
    }

    public function historyIndex()
    {
        $histories = VsOneSchedule::whereHas('game_master', function($q) {
            $q->whereHas('user', function($q) {
                $q->where('id', auth()->user()->id);
            });
        })->orderBy('game_master_id', 'asc')->orderBy('game_status', 'desc')->get()->reject(function ($query){
            if($query->game_status=='finish'){
                return true;
            }
        });
        return view('admin.history_schedule.vs_one', compact('histories'));
    }

    public function historyShow($game_master, $type)
    {
        $awards = Award::where('type_id', $type)->orderBy('nominal','desc')->get();
        $schedules = VsOneSchedule::where('game_master_id', '=', $game_master)
                        ->where('type_id', '=', $type)->get();
        $game = Game::find($schedules->first()->game_id);
        $type = Type::find($type);
        return view('admin.history_schedule.vs_one_show', compact('schedules','game', 'type', 'awards', 'game_master'));
    }

    public function winner($game_master, Request $request)
    {
        $all_request = $request->except('_token');

        foreach ($all_request as $key => $request) {
            $player = Player::where('email', $all_request[$key])->first();
            if(!$player) {
                return redirect()->route('admin.history_schedule.show', [
                    $key => $all_request[$key]
                ])->with(['error' => 'Email ' . $all_request[$key] . ' not found !']);
            } 
        }

        foreach ($all_request as $key => $request) {
            $player = Player::where('email', $all_request[$key])->first();
            $player->crown += (int) $key;
            $player->win_total += 1;
            $player->save();
            VsOneSchedule::where('game_master_id', $game_master)->update([
                'game_status' => 'finish', 
                'winner' => $all_request[$key]
            ]);
        }

        $vsOne = VsOneSchedule::where('game_master_id', $game_master)->first();
        $this->broadcastMessage('Game has ended', 'Check your game in the profile', $vsOne);
        return redirect()->route('admin.vs_one.show', [
            'game_master' => $game_master,
            'type' => $vsOne->type_id
        ])->with(['success' => 'Success Assign Winner']);
    }

    public function refund($id)
    {
        $vsOne = VsOneSchedule::find($id);
        $type = Type::find($vsOne->type_id);
        $player = Player::find($vsOne->player_id);
        $vsOne->update([
            'game_status' => 'canceled',
        ]);
        $player->crown += $type->crown_price;
        $player->save();
        return redirect()->route('admin.vs_one_schedule.index')->with(['success' => 'Berhasil direfund']);
    }
}
