<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            "birth_date" => "required|date",
            "gender" => "required|in:male,female,other",
        ]);

        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Validation errors",
                "errors" => $validator->errors()
            ],400);
        }

        $user_inputs = $validator->safe()->only(["name","email","password"]);
        $profile_inputs = $validator->safe()->except(["name","email","password"]);

        $user = User::create([$user_inputs]);
        $user->profile()->create($profile_inputs);

        return response()->json([
            "ok" => true,
            "message" => "User registered successfully!",
            "data" => $user
        ],201);
    }
}
