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
        ]);

        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Validation errors",
                "errors" => $validator->errors()
            ],400);
        }
        $inputs = $validator->safe()->only(["name","email","password"]);
        $user = User::create([$inputs]);
        return response()->json([
            "ok" => true,
            "message" => "User created successfully!",
            "data" => $user
        ],201);
    }

    public function index() {
        $users = User::all();
        return response()->json([
            "ok" => true,
            "message" => "Users retrieved successfully!",
            "data" => $users
        ],200);
    }

    public function show($id) {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                "ok" => false,
                "message" => "User not found"
            ],404);
        }
        return response()->json([
            "ok" => true,
            "message" => "User retrieved successfully!",
            "data" => $user
        ],200);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                "ok" => false,
                "message" => "User not found"
            ],404);
        }

        $validator = validator::make($request->all(),[
            "name" => "sometimes|string|unique:users,name,".$id,
            "email" => "sometimes|string|email|unique:users,email,".$id,
            "password" => "sometimes|string|confirmed|min:6",
        ]);

        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Validation errors",
                "errors" => $validator->errors()
            ],400);
        }

        $inputs = $validator->safe()->only(["name","email","password"]);
        $user->update($inputs);

        return response()->json([
            "ok" => true,
            "message" => "User updated successfully!",
            "data" => $user
        ],200);
    }

    public function destroy($id) {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                "ok" => false,
                "message" => "User not found"
            ],404);
        }
        $user->delete();
        return response()->json([
            "ok" => true,
            "message" => "User deleted successfully!"
        ],200);
    }
}
