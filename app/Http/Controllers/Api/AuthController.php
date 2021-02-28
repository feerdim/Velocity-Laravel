<?php

namespace App\Http\Controllers\Api;
use Google_Client; 
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\Mmr;
use App\Models\PlayerSchedule;
use App\Models\VsOneSchedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{   
     /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['register', 'login', 'googleLogin']);
    } 
    
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:players',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $player = Player::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'crown' => 0,
            'score' => 0,
            'avatar' => 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $request->name) . '&background=327fc2&color=ffffff&size=100'
        ]);

        $token = JWTAuth::fromUser($player);

        if($player) {
            return response()->json([
                'success' => true,
                'user'    => $player,  
                'token'   => $token  
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 500);
    }
    
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        if(!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is incorrect'
            ], 401);
        }
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),  
            'token'   => $token   
        ], 200);
    }
    
    /**
     * google Login
     *
     * @param  mixed $request
     * @return void
     */
    public function googleLogin(Request $request) 
    {
        $google_client_id = Config::get('app.google_client_id');
        $client = new Google_Client(['client_id' => $google_client_id]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($request->id_token);
        if ($payload) {
            $player = Player::where('email', $payload['email'])->first();
            if(!$player) {
                // random password
                $length = 10;
                $random = '';
                for ($i = 0; $i < $length; $i++) {
                    $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
                }

                $randomString = Str::upper($random);
                
                $player = Player::create([
                    'name'      => $payload['name'],
                    'email'     => $payload['email'],
                    'password'  => Hash::make($randomString),
                    'crown' => 0,
                    'score' => 0,
                    'avatar' => $payload['picture']
                ]);
                $token = JWTAuth::fromUser($player);
                return response()->json([
                    'success' => true,
                    'user'    => $player,  
                    'token'   => $token   
                ], 200);
            } else {
                $token = JWTAuth::fromUser($player);
                return response()->json([
                    'success' => true,
                    'user'    => $player,  
                    'token'   => $token   
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'cannot login with google'
            ], 401);
        }

    }
    
    /**
     * getUser
     *
     * @return void
     */
    public function getProfile()
    {
        $mmr = Mmr::where('min_rate', '<=', auth()->user()->score)->where('max_rate', '>', auth()->user()->score)->first();
        $activations = Activation::where('player_id', auth()->user()->id)->with('game')->get();
        $competitions = PlayerSchedule::where('player_id', auth()->user()->id)->with(['game','type','schedule','game_master'])->orderBy('game_status','DESC')->get();
        $vsOnes = VsOneSchedule::where('player_id', auth()->user()->id)->with(['game','type','game_master'])->orderBy('game_status','DESC')->get();
        return response()->json([
            'success' => true,
            'user'    => auth()->user(),
            'mmr'     => $mmr,
            'activations' => $activations,
            'competitions' => $competitions,
            'vsOnes' => $vsOnes
        ], 200);
    }
    
}