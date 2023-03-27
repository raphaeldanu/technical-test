<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logInApi(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);
     
        $user = User::where('username', $request->username)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            $response = ['message' => 'Bad credentials.',];
            return response($response, 401);
        }
        
        $token = $user->createToken('tech-test')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($data, 200);
    }

    public function logOutApi(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response(
            ['message' => 'Logged Out.'], 
            200
        );
    }

    public function getProfile(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
