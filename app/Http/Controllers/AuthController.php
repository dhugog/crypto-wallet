<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['data' => $user, 'message' => 'Successfully registered.'], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
