<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtodo extends Model
{
    public function todo (){
        return $this->belongsTo(Todo::class);
    }
}
