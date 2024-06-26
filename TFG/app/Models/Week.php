<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'n_week'];

    public function tourns(){
        return $this->hasMany(Tourn::class);
    }

    public function availabilities(){
        return $this->hasMany(Availability::class);
    }
}
