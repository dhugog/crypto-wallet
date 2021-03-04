<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
            'name'  => $request->name,
            'email' => $request->email
        ]);

        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['message' => 'Cadastro realizado com sucesso!'], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials))
            throw new BadRequestHttpException('Credenciais incorretas.');

        return $this->respondWithToken($token);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60
        ]);
    }
}
