<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use App\Models\WalletDeposit;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class WalletController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function balance(Request $request)
    {
        $wallet = $request->user()->wallet;
        return response()->json([
            'fiat_balance' => $wallet->balance,
            'gold_balance' => $wallet->gold_balance,
        ]);
    }

    public function deposit(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $this->walletService->depositFiat($request->user()->id, $request->amount);

        return response()->json(['message' => 'Deposit successful']);
    }

    public function withdraw(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $this->walletService->withdrawFiat($request->user()->id, $request->amount);

        return response()->json(['message' => 'Withdrawal successful']);
    }

   
    public function jazzcashPayment(Request $request)
    {
        try {
            $amount = $request->amount * 100;

            $pp_MerchantID  = env('JAZZCASH_MERCHANT_ID');
            $pp_Password    = env('JAZZCASH_PASSWORD');
            $IntegeritySalt ="08z3t4096c";
            $pp_TxnRefNo    = 'T' . time() . rand(1000, 9999);
            $dateTime       = now("Asia/Karachi")->format('YmdHis');
            $expiryDateTime = now("Asia/Karachi")->addMinutes(30)->format('YmdHis');

            // $data = [
            //     "pp_Version" => "1.1",
            //     "pp_TxnType" => "MWALLET",
            //     "pp_Language" => "EN",

            //     "pp_MerchantID" => $pp_MerchantID,
            //     "pp_Password" => $pp_Password,
            //     "pp_TxnRefNo" => $pp_TxnRefNo,
            //     "pp_Amount" => (int)$amount,
            //     "pp_TxnCurrency" => "PKR",
            //     "pp_TxnDateTime" => $dateTime,
            //     "pp_TxnExpiryDateTime" => $expiryDateTime,
            //     "pp_BillReference" => "billRef",
            //     "pp_Description" => "Wallet Deposit",
            //     // "pp_ReturnURL" => url('/wallet/jazzcash/callback'),
            //     "pp_ReturnURL" => 'http://127.0.0.1:8000/wallet/jazzcash/callback',
            //     // "pp_MobileNumber" => '92' . ltrim($request->user()->mobile_number, '0'),
            //     // "pp_MobileNumber" => '92' . ltrim('3123456789', '0'),
            
            //     "ppmpf_1" => "03222852628", // Optional - can be used to pass additional info
            // ];
          $data = [
            "pp_Version"           => "1.1",
            "pp_TxnType"           => "MWALLET", 
            "pp_Language"          => "EN",
            "pp_MerchantID"        => $pp_MerchantID,
            "pp_Password"          => $pp_Password,
            "pp_TxnRefNo"          => $pp_TxnRefNo,
            "pp_Amount"            => (int)$amount,
            "pp_TxnCurrency"       => "PKR",
            "pp_TxnDateTime"       => $dateTime,
            "pp_TxnExpiryDateTime" => $expiryDateTime,
            "pp_BillReference"     => "billRef",
            "pp_Description"       => "Wallet Deposit",
            "pp_ReturnURL"         => url('/wallet/jazzcash/callback'),
            "ppmpf_1"              => "03321234567", // Optional - can be used to pass additional info
            // "pp_MobileNumber" => "92323456789", // Use actual user mobile number here
        ];

            $data['pp_SecureHash'] = $this->generateSecureHash($data, $IntegeritySalt);

            // Store transaction reference in session/DB
            session([
                'jazzcash_txn_ref' => $pp_TxnRefNo,
                'jazzcash_user_id' => auth()->id(),
                'jazzcash_amount'  => $amount,
            ]);

            return view('jazzcash.redirect', compact('data'));

        } catch (\Exception $e) {
            \Log::error('JazzCash Payment Error', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            dd($e->getMessage());
             // return redirect('/wallet')->with('error', 'Error initiating payment');
        }
    }

    private function generateSecureHash($data, $integritySalt)
    {
        $hashString = 
            trim($integritySalt) . '&' .
            trim($data['pp_Amount']) . '&' .
            trim($data['pp_BillReference']) . '&' .
            trim($data['pp_Description']) . '&' .
            trim($data['pp_Language']) . '&' .
            trim($data['pp_MerchantID']) . '&' .
            trim($data['pp_Password']) . '&' .
            trim($data['pp_ReturnURL']) . '&' .
            trim($data['pp_TxnCurrency']) . '&' .
            trim($data['pp_TxnDateTime']) . '&' .
            trim($data['pp_TxnExpiryDateTime']) . '&' .
            trim($data['pp_TxnRefNo']) . '&' .
            trim($data['pp_TxnType']) . '&' .
            // trim($data['pp_MobileNumber']) . '&' .
            trim($data['pp_Version'] ?? '') . '&' .
            $data['ppmpf_1'] ?? '';
            // trim($data['ppmpf_1']);
            

        return strtoupper(
            hash_hmac('sha256', $hashString, trim($integritySalt))
        );
    }

    public function jazzcashCallback(Request $request)
    {
        \Log::info('JazzCash Callback', $request->all());

        try {
            // Get user from authenticated session
            $user = auth()->user();

            if (!$user) {
                \Log::error('No authenticated user in callback');
                return redirect('/login')->with('error', 'Please login first');
            }

            // Build callback data for hash verification
            $callbackData = [
                "pp_MerchantID"        => $request->pp_MerchantID,
                "pp_Password"          => env('JAZZCASH_PASSWORD'),
                "pp_TxnType"           => $request->pp_TxnType,
                "pp_Language"          => $request->pp_Language,
                "pp_SubMerchantID"     => $request->pp_SubMerchantId ?? "",
                "pp_BankID"            => $request->pp_BankID ?? "",
                "pp_ProductID"         => $request->pp_ProductID ?? "",
                "pp_TxnRefNo"          => $request->pp_TxnRefNo,
                "pp_Amount"            => $request->pp_Amount,
                "pp_TxnCurrency"       => $request->pp_TxnCurrency,
                "pp_TxnDateTime"       => $request->pp_TxnDateTime,
                "pp_TxnExpiryDateTime" => $request->pp_TxnExpiryDateTime,
                "pp_BillReference"     => $request->pp_BillReference,
                "pp_Description"       => $request->pp_Description,
                "pp_ReturnURL"         => $request->pp_ReturnURL,
                "pp_ResponseCode"      => $request->pp_ResponseCode,
                "pp_ResponseMessage"   => $request->pp_ResponseMessage,
                "pp_TxnRefNoPartner"   => $request->pp_TxnRefNoPartner ?? "",
                "pp_TxnIdPartner"      => $request->pp_TxnIdPartner ?? "",
            ];

            // Verify hash
            $calculatedHash = $this->verifySecureHash($callbackData, env('JAZZCASH_INTEGERITY_SALT'));

            \Log::info('Hash Verification', [
                'received' => $request->pp_SecureHash,
                'calculated' => $calculatedHash,
                'match' => strtoupper($request->pp_SecureHash) === strtoupper($calculatedHash),
            ]);

            // Check response code
            if ($request->pp_ResponseCode == '000000') {
                // SUCCESS - Add funds
                $amount = $request->pp_Amount / 100;

                $user->update([
                    'wallet_balance' => $user->wallet_balance + $amount
                ]);

                // Record deposit
                $walletDeposit = WalletDeposit::firstOrNew(['user_id' => $user->id]);
                $walletDeposit->amount = ($walletDeposit->amount ?? 0) + $amount;
                $walletDeposit->transaction_ref = $request->pp_TxnRefNo;
                $walletDeposit->response_code = '000000';
                $walletDeposit->response_message = $request->pp_ResponseMessage;
                $walletDeposit->status = 'success';
                $walletDeposit->raw_response = $request->all();
                $walletDeposit->save();

                \Log::info('Payment Success', [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'new_balance' => $user->wallet_balance,
                ]);

                return redirect('/wallet')->with('success', "Rs. {$amount} added to your wallet!");
            }

            // FAILED
            WalletDeposit::create([
                'user_id'          => $user->id,
                'amount'           => $request->pp_Amount / 100,
                'transaction_ref'  => $request->pp_TxnRefNo,
                'response_code'    => $request->pp_ResponseCode,
                'response_message' => $request->pp_ResponseMessage,
                'status'           => 'failed',
                'raw_response'     => $request->all(),
            ]);

            \Log::warning('Payment Failed', [
                'code'    => $request->pp_ResponseCode,
                'message' => $request->pp_ResponseMessage,
            ]);

            return redirect('/wallet')->with('error', 'Payment failed: ' . $request->pp_ResponseMessage);

        } catch (\Exception $e) {
            \Log::error('Callback Error', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return redirect('/wallet')->with('error', 'Error processing payment');
        }
    }

   private function verifySecureHash($data, $salt)
    {
        return $this->generateSecureHash($data, $salt);
    }
//test tgus
    public function stripePayment(Request $request)
    {
        try {
            $amount = $request->amount;
            if (!$amount || $amount <= 0) {
                return redirect('/wallet')->with('error', 'Invalid amount');
            }

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => $amount * 100,
                        'product_data' => [
                            'name'  => 'Wallet Deposit',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('stripe.cancel'),
                'metadata' => [
                    'user_id' => auth()->id()
                ]
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            // return redirect('/dashboard')->with('error', $e->getMessage());
            dd($e->getMessage());
        }
    }

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');

        try {
            $session = Session::retrieve($sessionId);
            if (!$session || $session->payment_status !== 'paid') {
                return redirect('/wallet')->with('error', 'Payment not completed.');
            }

            $user = \App\Models\User::find($session->metadata->user_id);
            if (!$user) {
                return redirect('/wallet')->with('error', 'User not found.');
            }

            $amount = $session->amount_total / 100;

            $user->update([
                'wallet_balance' => $user->wallet_balance + $amount
            ]);

            $walletDeposit = WalletDeposit::firstOrNew(['user_id' => $user->id]);
            
            $walletDeposit->amount           = ($walletDeposit->amount ?? 0) + $amount;
            $walletDeposit->transaction_ref  = $session->payment_intent;
            $walletDeposit->response_code    = 'success';
            $walletDeposit->response_message = 'Stripe deposit';
            $walletDeposit->status           = 'success';
            $walletDeposit->raw_response     = $session->toArray();
            $walletDeposit->save();

            return redirect('/dashboard')->with('success', "\${$amount} added to your wallet via Stripe!");
        } catch (\Exception $e) {
            // return redirect('/wallet')->with('error', 'Error verifying Stripe payment.');
            dd($e->getMessage());
        }
    }

    public function stripeCancel()
    {
        return redirect('/wallet')->with('error', 'Stripe payment was cancelled.');
    }
}
