<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function wallet(){
        try {
            $wallet  = Wallet::select('user_id', 'balance')->where('user_id', auth()->id())->first();
            return sendResponse(true, 'success', $wallet);
        }catch (\Exception $exception){
            return sendResponse(false, 'something went wrong!');
        }

    }
    public function walletHistory()
    {
        try {
            $wallet  = Wallet::select('id', 'user_id', 'balance')->where('user_id', auth()->id())->with('walletHistory')->first();
            $data = new WalletResource($wallet);
            return sendResponse(true, 'success', $data);
        }catch (\Exception $exception){
            return sendResponse(false, 'something went wrong!');
        }

    }
    public function walletWithdraw(){

    }
}
