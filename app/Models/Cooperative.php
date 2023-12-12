<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'location_id',
        'village',
        'parish',
        'zone',
        'production_type',
        'date_of_establishment',
        'certification',
        'description',
        'applicant_id',
        
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
           
        });

          //call back to send a notification to the user
          self::created(function ($model) 
          {
              Notification::send_notification($model, 'Cooperative', request()->segment(count(request()->segments())));
          });

            //call back to send a notification to the user
            self::updated(function ($model) 
            {
                Notification::update_notification($model, 'Cooperative', request()->segment(count(request()->segments())-1));
            
                if($model->status == 'approved'){
                    AdminRoleUser::where([
                        'user_id' => $model->user_id
                    ])->delete();
                    $new_role = new AdminRoleUser();
                    $new_role->user_id = $model->user_id;
                    $new_role->role_id = 8;
                    $new_role->save();
                }
            });

    }
}
