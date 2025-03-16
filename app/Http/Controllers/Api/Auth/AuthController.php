<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new CustomException('Password Not Matched',  404);
            }
            return sendResponse(true, 'Login Successfully', ['token' => $user->createToken('auth_token')->plainTextToken]);
        }catch (\Exception $exception){
            return sendResponse(false, 'Something Went Wrong', $exception->getCode());
        }

    }
}
