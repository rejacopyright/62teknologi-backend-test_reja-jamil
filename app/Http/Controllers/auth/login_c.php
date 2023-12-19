<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class login_c extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'custom']]);
    }
    function login(Request $r)
    {
        $credentials = $r->only('username', 'password');
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'username dan password tidak cocok']);
        } else {
            // return auth('api')->user();
            $token = auth('api')->claims(['updated_at' => strtotime('now'), 'user' => auth('api')->user()->first()])->attempt($credentials);
            $user = auth('api')->user();
            $token = $this->respondWithToken($token)->original;
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'berhasil login'
            ]);
            return response()->json(compact('user', 'token'));
        }
    }

    public function logout()
    {
        auth()->logout(true);
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1
        ]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
