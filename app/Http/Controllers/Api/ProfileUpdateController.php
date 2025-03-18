<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileUpdateResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateController extends Controller
{
    public function UserProfileShow(){
         $user = Auth::user();
        $data =  new profileUpdateResource($user);
        return sendResponse(true, 'User profile fetched successfully.', $data, 200);
    }
    public function userProfileUpdate(Request $request){
        //return $request->all();
        request()->validate([
            'name' => 'required',
            'email' => 'required|string|email|exists:users,email',
            'gender' => 'required',
        ]);
        try {
            $user = Auth::user();
            $user->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'profile_image' => $request->profile_image,
                'dod' => $request->dod,
                'gender' => $request->gender,
                'status' => 2,

            ]);
            $data =  new profileUpdateResource($user);
            return sendResponse(true, 'User profile updated successfully.', $data, 200);
        }catch (\Exception $exception){
            return sendResponse(false, 'Something went wrong!', null, 500);
        }

    }
}
