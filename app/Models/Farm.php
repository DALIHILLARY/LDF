<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'coordinates',
        'breeds',
        'location',
        'village',
        'parish',
        'zone',
        'livestock_type',
        'production_type',
        'date_of_establishment',
        'size',
        'profile_picture',
        'number_of_workers',
        'land_ownership',
        'no_land_ownership_reason',
        'general_remarks',
        'owner_id',
        'added_by'

    ];

    public function farmOwner()
    {
        return $this->belongsTo(Farmer::class, 'owner_id');
    }

  
}
