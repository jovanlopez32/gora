<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    public function tags(){
        return $this->hasMany(Tag::class);
    }

    public function countreports(){
        return $this->hasMany(CountReport::class);
    }


    public function problemreports(){
        return $this->hasMany(ProblemReport::class);
    }

}
