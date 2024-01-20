<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountReport extends Model
{
    use HasFactory;

    public function device(){
        return $this->belongsTo(Device::class);
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function tag(){
        return $this->belongsTo(Tag::class);
    }
}
