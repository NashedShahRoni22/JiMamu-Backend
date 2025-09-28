<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function walletProcessing(){
        try {
            $wallet = Wallet::where('user_id', auth()->id())
                ->with(['walletHistory' => function ($query) {
                    $query->whereIn('status', [1, 2, 3, 4]); // 1 = pending, 2 = approved
                }])
                ->firstOrFail();
            $data = new WalletResource($wallet);
            return sendResponse(true, 'success', $data);
        }catch (\Exception $exception){
            return sendResponse(false, $exception->getMessage());
        }
    }

    // only pending and processing transaction
    public function walletWithdrawal(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $wallet = Wallet::where('user_id', auth()->id())
                    ->lockForUpdate() // lock row until transaction finishes
                    ->firstOrFail();

                if ((float) $request->amount > (float) $wallet->balance) {
                    throw new CustomException('Your wallet has insufficient balance');
                }

                // create wallet history entry
                $wallet->walletHistory()->create([
                    'user_id'               => auth()->id(),
                    'amount'                => $request->amount,
                    'purpose_of_transaction'=> WalletHistory::$PURPOSE_OF_TRANSACTION['withdrawal'],
                    'transaction_type'      => WalletHistory::$TRANSACTION_TYPE['debit'],
                    'status'                => WalletHistory::$STATUS['pending'],
                ]);

                // calculate new balance
                $newBalance = (float) $wallet->balance - (float) $request->amount;

                // update wallet balance
                $wallet->update([
                    'balance' => $newBalance,
                ]);
            });

            return sendResponse(true, 'Your withdrawal request has been sent.', null, 201);

        } catch (CustomException $e) {
            return sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return sendResponse(false, 'Something went wrong!');
        }
    }

    // only show completed transaction cancel or approved
    public function walletHistory()
    {
        try {
            $wallet = Wallet::where('user_id', auth()->id())
                ->with(['walletHistory' => function ($query) {
                    $query->whereIn('status', [1, 2, 3, 4]); // 3 = approved, 4 = cancelled
                }])
                ->firstOrFail();
            $data = new WalletResource($wallet);
            return sendResponse(true, 'success', $data);
        }catch (\Exception $exception){
            return sendResponse(false, 'something went wrong!');
        }

    }

}
