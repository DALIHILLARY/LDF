<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'owner_name',
        'owner_profile',
        'class_of_service',
        'date_of_registration',
        'physical_address',
        'primary_phone_number',
        'secondary_phone_number',
        'email',
        'postal_address',
        'other_services',
        'logo',
        'distroict_of_operation',
        'NDA_registration_number',
        'tin_number_business',
        'tin_number_owner',
        'license',
        'other_documents',
        'status',
        'user_id',
        'added_by'


    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //check if its the admin creating the form or ldf
            if(auth('admin')->user()->inRoles(['administrator','ldf_admin']))
            {
                //check if the user with the same email exists
                $user = User::where('email', $model->email) 
                ->orWhere('username', $model->email)
                ->first();
                if(!$user){
                   //create a new user and assign the user_id to the vet
                    $new_user = new User();
                    $new_user->username = $model->email;
                    $new_user->name = $model->surname.' '.$model->given_name;
                    $new_user->email = $model->email;
                    $new_user->password = bcrypt('password');
                    $new_user->avatar = $model->profile_picture ? $model->profile_picture : 'images/default_image.png';
                    $new_user->save();

                    
                    $model->user_id = $new_user->id;
                }
               
            }
           
        });

          //call back to send a notification to the user
          self::created(function ($model) 
          {
               

                Notification::send_notification($model, 'ServiceProvider', request()->segment(count(request()->segments())));

                if(auth('admin')->user()->inRoles(['administrator','ldf_admin']))
                {
                    $new_user = User::where('email', $model->email)
                    ->orWhere('username', $model->email)
                    ->first();
                    $new_role = new AdminRoleUser();
                    $new_role->role_id = 3;
                    $new_role->user_id = $new_user->id;
                    $new_role->save();
                }

          });


           //callback to create a user with the vet credentials after if the status is approved 
           self::updating(function ($model){
                if($model->status == 'approved'){
                    $user = User::where('email', $model->email) 
                    ->orWhere('username', $model->email)
                    ->first();
                    if(!$user){
                       //create a new user and assign the user_id to the vet
                        $new_user = new User();
                        $new_user->username = $model->email;
                        $new_user->name = $model->surname.' '.$model->given_name;
                        $new_user->email = $model->email;
                        $new_user->password = bcrypt('password');
                        $new_user->avatar = $model->profile_picture ? $model->profile_picture : 'images/default_image.png';
                        $new_user->save();
    
                        
                        $model->user_id = $new_user->id;
                    }
                }

            });
           

            //call back to send a notification to the user
            self::updated(function ($model) 
            {
                Notification::update_notification($model, 'ServiceProvider', request()->segment(count(request()->segments())-1));

                $new_user = User::where('email', $model->email)
                ->orWhere('username', $model->email)
                ->first();
                $new_role = new AdminRoleUser();
                $new_role->role_id = 3;
                $new_role->user_id = $new_user->id;
                $new_role->save();
                
 
            });

    
         
        

    }
}