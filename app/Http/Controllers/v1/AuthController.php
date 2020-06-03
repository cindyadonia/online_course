<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $data = $request->validated();
        if(!auth()->attempt($request->all())){
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'You are unauthorized!',
                'data' => []
                ]);
        }

        $role = auth()->user()->role->name;
        
        // Create Passport token
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $data = [
            'user' => auth()->user(),
            'access_token' => $accessToken
        ];
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Sucessfully login to the system',
            'data' => $data
            ]);
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Sucessfully logged out!',
            'data' => []
        ]);
    }
}
