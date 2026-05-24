<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function jazzcashPayment(Request $request)
    {
        $amount = $request->amount * 100;

        $pp_MerchantID = env('JAZZCASH_MERCHANT_ID');
        $pp_Password = env('JAZZCASH_PASSWORD');
        $IntegeritySalt = env('JAZZCASH_INTEGERITY_SALT');

        $pp_TxnRefNo = 'T' . time();

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
            "pp_Amount" => $amount,
            "pp_TxnCurrency" => "PKR",
            "pp_TxnDateTime" => now()->format('YmdHis'),
            "pp_BillReference" => "billRef",
            "pp_Description" => "Wallet Deposit",
            "pp_TxnExpiryDateTime" => now()->addHour()->format('YmdHis'),
            "pp_ReturnURL" => url('/wallet/jazzcash/callback'),
        ];

        return view('jazzcash.redirect', compact('data'));
    }
}
