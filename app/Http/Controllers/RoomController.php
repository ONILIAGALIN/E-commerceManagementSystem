<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Validator;
class RoomController extends Controller
{
    public function store (Request $request){
        $validator = validator::make($request->all(),[
        "number" => "required|string|unique:rooms,number",
        "type" => "required|in:single,double,Deluxe,VIP,suite",
        "floor" => "required|string",
        "status" => "required|in:available,occupied,maintenance",
        "image" => "sometimes|image|mimes:jpeg,png,pjpg,gif,svg|max:2048",
        ]);

        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Request didn't pass the validation",
                "errors" => $validator->errors()
            ],400);
        }

        $validated = $validator->validated();
        if(isset($validated["image"])){
            $image = $request->file("image");
        }

        $room = Room::create([
            "number" => $validated["number"],
            "type" => $validated["type"],
            "floor" => $validated["floor"],
            "status" => $validated["status"],
        ]);

        return response()->json([
            "ok" => true,
            "message" => "Room create Successfully!",
            "data" => $room
        ],201);
    }

    public function index (){
        $rooms = Room::all();
        
        return response()->json([
            "ok" => true,
            "message" => "Rooms retrieved successfully!",
            "data" => $rooms
        ],200);
    }
}
