<?php

namespace App\Http\Controllers\Admin;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class PlayerController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:players.index|players.create|players.edit|players.delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::latest()->get();

        return view('admin.player.index', compact('players'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.player.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:players',
            'password' => 'required|confirmed',
        ]);

        $player = Player::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
        ]);

        if($player){
            //redirect dengan pesan sukses
            return redirect()->route('admin.player.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.player.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
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
        $player = Player::findOrFail($id);
        
        $player->delete();

        if($player){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function showActivation(Player $player)
    {
        return view('admin.player.activation', compact('player'));
    }

    public function updateActivation(Player $player, Request $request)
    {
        // $fcm_key = env('FCM_SERVER_KEY');

        $activation = $player->activations->where('id','=', $request->activation_id)->first();
        $activation->status = $request->status;
        $activation->save();
        if($activation){
            // $response = Http::withHeaders([
            //     "Authorization" => "key=" . $fcm_key
            // ])->post('https://fcm.googleapis.com/fcm/send', [
            //     "topic" => $request->topic,
            //     "type" => 1,
            //     "start_time" => $request->start_time,
            //     "duration" => $request->duration,
            //     "timezone" => "Asia/Jakarta",
            //     "password" => $request->password,
            //     "agenda" => $request->agenda,
            //     "settings" => [
            //         "host_video" => false,
            //         "participant_video" => false
            //     ]
            // ]);
            if($activation->status == 'true') {
                $this->broadcastMessage('Game Account has been activated','Please Refresh your browser', $player->id, $activation->game->slug);
            } else {
                $this->broadcastMessage('Game Account is not valid','Please Re-enter your account id', $player->id, $activation->game->slug);
            }
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    private function broadcastMessage($title, $message, $user_id, $game_slug)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            ->setClickAction(env('APP_PLAYER_URL'));

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'message' => $message,
            'url' => env('APP_PLAYER_URL') . '/game'. '/' . $game_slug
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = Player::where('id',$user_id)->pluck('fcm_token')->toArray();
        
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        
        return $downstreamResponse->numberSuccess();
    }
}
