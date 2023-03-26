<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except("login");
    }
    public function verifyToken()
    {
        return ["verified" => true];
    }
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = Auth::guard("admin")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function logout()
    {
        auth()->logout();
    }
    //Commons
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
