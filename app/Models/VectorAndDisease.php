<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VectorAndDisease extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'farm_id',
        'animal_id',
        'name',
        'image',
        'description',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
