<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller{
    
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = user::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('recetapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        /** @var User */
        $user = User::where('email',$data['email'])->first();
        $token = $user->createToken('recetapptoken')->plainTextToken;

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response([
                'mensaje' => 'Credenciales incorrectas'
            ], 401);
        }

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function logout(Request $request){
        /** @var User */
        $user = auth()->user();
        $user->tokens()->delete();

        return [
            'mensaje' => 'Logged out'
        ];
    }

}