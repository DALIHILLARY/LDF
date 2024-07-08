<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'profile_picture',
        'surname',
        'given_name',
        'date_of_birth',
        'nin',
        'location_id',
        'village',
        'parish',
        'zone',
        'gender',
        'marital_status',
        'number_of_dependants',
        'farmer_group',
        'primary_phone_number',
        'secondary_phone_number',
        'is_land_owner',
        'land_ownership',
        'production_scale',
        'access_to_credit',
        'credit_institution',
        'date_started_farming',
        'highest_level_of_education',
        'admin_remarks',
        'status',
        'user_id',
        'added_by',
        

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            
                //check if the user with the same primary_phone_number exists
                $user = User::where('email', $model->primary_phone_number) 
                ->orWhere('username', $model->primary_phone_number)
                ->first();
                if(!$user){
                   //create a new user and assign the user_id to the vet
                    $new_user = new User();
                    $new_user->username = $model->primary_phone_number;
                    $new_user->name = $model->surname.' '.$model->given_name;
                    $new_user->email = $model->primary_phone_number;
                    $new_user->password = bcrypt('password');
                    $new_user->avatar = $model->profile_picture ? $model->profile_picture : 'images/default_image.png';
                    $new_user->save();

                    
                    $model->user_id = $new_user->id;
                }
               
          
           
        });

          //call back to send a notification to the user
          self::created(function ($model) 
          {
               

                Notification::send_notification($model, 'Farmer', request()->segment(count(request()->segments())));

                $new_user = User::where('email', $model->primary_phone_number)
                ->orWhere('username', $model->primary_phone_number)
                ->first();
                $new_role = new AdminRoleUser();
                $new_role->role_id = 3;
                $new_role->user_id = $new_user->id;
                $new_role->save();
                

          });


           //callback to create a user with the vet credentials after if the status is approved 
           self::updating(function ($model){
               
            });
           

            //call back to send a notification to the user
            self::updated(function ($model) 
            {
                Notification::update_notification($model, 'Farmer', request()->segment(count(request()->segments())-1));

                
 
            });

    
         
        

    }
}
 