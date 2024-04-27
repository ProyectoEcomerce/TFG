<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_name',
        'mañana_start_time',
        'mañana_end_time',
        'tarde_start_time',
        'tarde_end_time',
        'noche_start_time',
        'noche_end_time'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
}
