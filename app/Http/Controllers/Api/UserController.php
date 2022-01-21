<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    //
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where("email",  "=", $request->email)->first();

        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "status" => "Ok.",
                    "msg" => "Usuario logeado.",
                    "access_token" => $token
                ]);
            }
        }

        return response()->json([
            "status" => "Error.",
            "msg" => "El Usuario no se encuentra registrado o la contraseña es incorrecta."
        ], 404);
    }

    //
    public function userProfile() {
        return response()->json([
            "status" => "Error.",
            "msg" => "InProgress."
        ], 404);
    }

    //
    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json([
            "msg" => "Usuario deslogeado."
        ]);
    }
}
