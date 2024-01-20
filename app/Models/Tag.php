<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function device(){
        return $this->belongsTo(Device::class);
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function countreports(){
        return $this->hasMany(CountReport::class);
    }

    public function problemreports(){
        return $this->hasMany(ProblemReport::class);
    }

}
