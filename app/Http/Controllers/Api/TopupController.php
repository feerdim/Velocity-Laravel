<?php

namespace App\Http\Controllers\Api;

// use Midtrans\Config;
use FCM;
use Midtrans\Snap;
use App\Models\Player;
use App\Models\TopupCharge;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;

use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class TopupController extends Controller
{
    protected $request;     

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->except('notificationHandler');

        $this->request = $request;
        // Set midtrans configuration
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    } 

    public function index()
    {
        $transactions = TopupCharge::where('player_id', auth()->guard('api')->user()->id)
            ->with('crown_package')
            ->orderBy('created_at','desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'All Transaction List',  
            'transactions' => $transactions
        ], 200);
    }

    public function store()
    {
        DB::transaction(function() {

            /**
             * algorithm create no invoice
             */
            $length = 10;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }
            $t=time();
            $timeStamp = date("Y-m-d",$t);
            $transaction_no = 'VCL-'.$timeStamp.'-'.Str::upper($random);
            $transaction = TopupCharge::create([
                'transaction_no' => $transaction_no,
                'status' => 'pending',
                'crown_package_id' => $this->request->crown_package_id,
                'player_id' => auth()->guard('api')->user()->id,
                'grand_total' => $this->request->grand_total,
                'payment_type' => $this->request->payment_type
            ]);

            // Buat transaksi ke midtrans kemudian save snap tokennya.
            $payload = [
                'transaction_details' => [
                    'order_id'      => $transaction->transaction_no,
                    'gross_amount'  => $this->request->grand_total,
                ],
                'customer_details' => [
                    'first_name'       => auth()->guard('api')->user()->name,
                    'email'            => auth()->guard('api')->user()->email
                ],
                'enabled_payments' => [$this->request->payment_type],
                'vtweb' => []
            ];
            

            //create snap token
            $snapToken = Snap::getSnapToken($payload);
            $transaction->snap_token = $snapToken;
            $transaction->save();

            $this->response = $transaction;


        });

        return response()->json([
            'success' => true,
            'message' => 'Top up Success',  
            'transaction' => $this->response
        ], 200);

    }

    private function broadcastMessage($title, $message, $player_id)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            ->setClickAction(env('APP_PLAYER_URL').'/transaction');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'message' => $message
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        $tokens = Player::where('id', $player_id)->pluck('fcm_token')->toArray();
        
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        
        return $downstreamResponse->numberSuccess();
    }
    
    /**
     * notificationHandler
     *
     * @param  mixed $request
     * @return void
     */
    public function notificationHandler(Request $request)
    {
        $payload      = $request->getContent();
        $notification = json_decode($payload);
      
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config('services.midtrans.serverKey'));

        if ($notification->signature_key != $validSignatureKey) {
            return response(['message' => 'Invalid signature'], 403);
        }

        $transaction  = $notification->transaction_status;
        $type         = $notification->payment_type;
        $orderId      = $notification->order_id;
        $fraud        = $notification->fraud_status;

        //data tranaction
        $topup_charge= TopupCharge::where('transaction_no', $orderId)->first();
        

        if ($transaction == 'capture') {
 
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {

              if($fraud == 'challenge') {
                
                /**
                *   update invoice to pending
                */
                $topup_charge->update([
                    'status' => 'pending'
                ]);

              } else {
                
                /**
                *   update invoice to success
                */
                $topup_charge->update([
                    'status' => 'success'
                ]);
                $topup_charge->player->crown += (int) $topup_charge->crown_package->name;
                $topup_charge->player->save();
                $this->broadcastMessage('Topup Success','Please refresh the website',$topup_charge->player->id);
                return response(['message' => 'Success'], 200);

              }

            }

        } elseif ($transaction == 'settlement') {

            /**
            *   update invoice to success
            */
            $topup_charge->update([
                'status' => 'success'
            ]);
            $topup_charge->player->crown += (int) $topup_charge->crown_package->name;
            $topup_charge->player->save();
            $this->broadcastMessage('Topup Success','Please refresh the website',$topup_charge->player->id);
            return response(['message' => 'Success'], 200);
            


        } elseif($transaction == 'pending'){

            
            /**
            *   update invoice to pending
            */
            $topup_charge->update([
                'status' => 'pending'
            ]);


        } elseif ($transaction == 'deny') {

            
            /**
            *   update invoice to failed
            */
            $topup_charge->update([
                'status' => 'failed'
            ]);


        } elseif ($transaction == 'expire') {

            
            /**
            *   update invoice to expired
            */
            $topup_charge->update([
                'status' => 'expired'
            ]);


        } elseif ($transaction == 'cancel') {

            /**
            *   update invoice to failed
            */
            $topup_charge->update([
                'status' => 'failed'
            ]);

        }

    }
}