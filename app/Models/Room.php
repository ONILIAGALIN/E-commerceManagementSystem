<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  protected $fillable = [ 
        "user_id",
        "number",
        "type",
        "floor",
        "status",
        "extension",
    ];

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }

}