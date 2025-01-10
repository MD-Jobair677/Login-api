<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Todo extends Model
{
    public function user (){
        return $this->belongsTo(User::class);
    }
}
