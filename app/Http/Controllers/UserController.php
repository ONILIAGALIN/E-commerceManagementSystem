<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store (Request $request) {
        $validator = validator::make($request->all(),[
            "name" => "required|string|unique:users,name",
            "email" => "required|string|email|unique:users,email",
            "password" => "required|string|confirmed|min:6",
            "first_name" => "required|string|min:2|max:32",
            "middle_name" => "sometimes|string|min:2|max:32",
            "last_name" => "reqiured|string|min:2|max:32",
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

    public function index(){
        return response()->json([
            "ok" => true,
            "message" => "User retrieved successfully!",
            "data" => User::with("profile")->get()
        ],200);
    }
}
