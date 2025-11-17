<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            "name" => "required|string|unique:users,name",
            "email" => "required|string|email|unique:users,email",
            "password" => "required|string|confirmed|min:6",
            "first_name" => "required|string|min:2|max:30",
            "middle_name" => "sometimes|string|min:2|max:30",
            "last_name" => "required|string|min:2|max:30",
            "contact_number" => "required|string|max:15",
        ]);

        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Validation errors",
                "errors" => $validator->errors()
            ],400);
        }

        $user_inputs = $validator->safe()->only(["name","email","password","role"]);
        $profile_inputs = $validator->safe()->except(["name","email","password", "role"]);

        $user = User::create([$user_inputs]);
        $user->profile()->create($profile_inputs);

        return response()->json([
            "ok" => true,
            "message" => "User registered successfully!",
            "data" => $user
        ],201);
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        "name" => "required",
        "password" => "required"
    ]);

    if ($validator->fails()) {
        return response()->json([
            "ok" => false,
            "message" => "Incorrect name or password",
            "errors" => $validator->errors()
        ], 400);
    }

    $validated = $validator->validated();

    $loginField = filter_var($validated["name"], FILTER_VALIDATE_EMAIL) ? "email" : "name";

    if (!Auth::attempt([
        $loginField => $validated["name"], 
        "password" => $validated["password"]
    ])) {
        return response()->json([
            "ok" => false,
            "message" => "Incorrect name or password"
        ], 401);
    }

    $user = auth()->user();
    $user->load('profile'); // load relationship

    $token = $user->createToken("api")->accessToken;

    return response()->json([
        "ok" => true,
        "message" => "Login Successfully!",
        "token" => $token,
        "user" => $user
    ]);
}
}