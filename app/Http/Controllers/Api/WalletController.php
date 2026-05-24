<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WalletService;

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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function jazzcashPayment(Request $request)
    {
        try {
            $amount = $request->amount * 100;

            $pp_MerchantID = env('JAZZCASH_MERCHANT_ID');
            $pp_Password = env('JAZZCASH_PASSWORD');
            $IntegeritySalt = env('JAZZCASH_INTEGERITY_SALT');

            $pp_TxnRefNo = 'T' . time() . rand(1000, 9999);

            $data = [
                "pp_Version" => "1.1",
                "pp_TxnType" => "MWALLET",
                "pp_Language" => "EN",
                "pp_MerchantID" => $pp_MerchantID,
                "pp_SubMerchantID" => "",
                "pp_Password" => $pp_Password,
                "pp_BankID" => "",
                "pp_ProductID" => "",
                "pp_TxnRefNo" => $pp_TxnRefNo,
                "pp_Amount" => (int)$amount,
                "pp_TxnCurrency" => "PKR",
                "pp_TxnDateTime" => now()->format('YmdHis'),
                "pp_BillReference" => "billRef",
                "pp_Description" => "Wallet Deposit",
                "pp_TxnExpiryDateTime" => now()->addHour()->format('YmdHis'),
                "pp_ReturnURL" => url('/wallet/jazzcash/callback'),
                // Remove ppmpf fields entirely
            ];

            $data['pp_SecureHash'] = $this->generateSecureHash($data, $IntegeritySalt);

            // Store transaction reference in session/DB
            session(['jazzcash_txn_ref' => $pp_TxnRefNo, 'jazzcash_user_id' => auth()->id()]);

            return view('jazzcash.redirect', compact('data'));

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function generateSecureHash($data, $salt)
    {
        $hashString = $salt;
        $hashString .= $data['pp_MerchantID'];
        $hashString .= $data['pp_Password'];
        $hashString .= $data['pp_TxnType'];
        $hashString .= $data['pp_Language'];
        $hashString .= $data['pp_MerchantID'];
        $hashString .= $data['pp_SubMerchantID'];
        $hashString .= $data['pp_BankID'];
        $hashString .= $data['pp_ProductID'];
        $hashString .= $data['pp_TxnRefNo'];
        $hashString .= $data['pp_Amount'];
        $hashString .= $data['pp_TxnCurrency'];
        $hashString .= $data['pp_TxnDateTime'];
        $hashString .= $data['pp_TxnExpiryDateTime'];
        $hashString .= $data['pp_BillReference'];
        $hashString .= $data['pp_Description'];
        $hashString .= $data['pp_ReturnURL'];
        // Add custom fields to hash
        $hashString .= $data['ppmpf_1'];
        $hashString .= $data['ppmpf_2'];
        $hashString .= $data['ppmpf_3'];
        $hashString .= $data['ppmpf_4'];
        $hashString .= $data['ppmpf_5'];

        return hash('sha256', $hashString);
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
                "pp_MerchantID" => $request->pp_MerchantID,
                "pp_Password" => env('JAZZCASH_PASSWORD'),
                "pp_TxnType" => $request->pp_TxnType,
                "pp_Language" => $request->pp_Language,
                "pp_SubMerchantID" => $request->pp_SubMerchantId ?? "",
                "pp_BankID" => $request->pp_BankID ?? "",
                "pp_ProductID" => $request->pp_ProductID ?? "",
                "pp_TxnRefNo" => $request->pp_TxnRefNo,
                "pp_Amount" => $request->pp_Amount,
                "pp_TxnCurrency" => $request->pp_TxnCurrency,
                "pp_TxnDateTime" => $request->pp_TxnDateTime,
                "pp_TxnExpiryDateTime" => $request->pp_TxnExpiryDateTime,
                "pp_BillReference" => $request->pp_BillReference,
                "pp_Description" => $request->pp_Description,
                "pp_ReturnURL" => $request->pp_ReturnURL,
                "pp_ResponseCode" => $request->pp_ResponseCode,
                "pp_ResponseMessage" => $request->pp_ResponseMessage,
                "pp_TxnRefNoPartner" => $request->pp_TxnRefNoPartner ?? "",
                "pp_TxnIdPartner" => $request->pp_TxnIdPartner ?? "",
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
                WalletDeposit::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'transaction_ref' => $request->pp_TxnRefNo,
                    'response_code' => '000000',
                    'response_message' => $request->pp_ResponseMessage,
                    'status' => 'success',
                    'raw_response' => $request->all(),
                ]);

                \Log::info('Payment Success', [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'new_balance' => $user->wallet_balance,
                ]);

                return redirect('/wallet')->with('success', "Rs. {$amount} added to your wallet!");
            }

            // FAILED
            WalletDeposit::create([
                'user_id' => $user->id,
                'amount' => $request->pp_Amount / 100,
                'transaction_ref' => $request->pp_TxnRefNo,
                'response_code' => $request->pp_ResponseCode,
                'response_message' => $request->pp_ResponseMessage,
                'status' => 'failed',
                'raw_response' => $request->all(),
            ]);

            \Log::warning('Payment Failed', [
                'code' => $request->pp_ResponseCode,
                'message' => $request->pp_ResponseMessage,
            ]);

            return redirect('/wallet')->with('error', 'Payment failed: ' . $request->pp_ResponseMessage);

        } catch (\Exception $e) {
            \Log::error('Callback Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect('/wallet')->with('error', 'Error processing payment');
        }
    }

    private function verifySecureHash($data, $salt)
    {
        $hashString = $salt;
        $hashString .= $data['pp_MerchantID'];
        $hashString .= $data['pp_Password'];
        $hashString .= $data['pp_TxnType'];
        $hashString .= $data['pp_Language'];
        $hashString .= $data['pp_MerchantID'];
        $hashString .= $data['pp_SubMerchantID'];
        $hashString .= $data['pp_BankID'];
        $hashString .= $data['pp_ProductID'];
        $hashString .= $data['pp_TxnRefNo'];
        $hashString .= $data['pp_Amount'];
        $hashString .= $data['pp_TxnCurrency'];
        $hashString .= $data['pp_TxnDateTime'];
        $hashString .= $data['pp_TxnExpiryDateTime'];
        $hashString .= $data['pp_BillReference'];
        $hashString .= $data['pp_Description'];
        $hashString .= $data['pp_ReturnURL'];
        $hashString .= $data['pp_ResponseCode'];
        $hashString .= $data['pp_ResponseMessage'];
        $hashString .= $data['pp_TxnRefNoPartner'];
        $hashString .= $data['pp_TxnIdPartner'];

        return hash('sha256', $hashString);
    }
//test tgus
}
