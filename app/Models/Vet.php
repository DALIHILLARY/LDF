<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vet extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable=[
        'profile_picture',
        'title',
        'category',
        'surname',
        'given_name',
        'nin',
        'coordinates',
        'location',
        'village',
        'parish',
        'zone',
        'group_or_practice',
        'license_number',
        'license_expiry_date',
        'date_of_registration',
        'brief_profile',
        'primary_phone_number',
        'secondary_phone_number',
        'email',
        'postal_address',
        'services_offered',
        'ares_of_operation',
        'certificate_of_registration',
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
               

                Notification::send_notification($model, 'Vet', request()->segment(count(request()->segments())));
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
                Notification::update_notification($model, 'Vet', request()->segment(count(request()->segments())-1));

                $new_user = User::where('email', $model->email)
                ->orWhere('username', $model->email)
                ->first();
                $new_role = new AdminRoleUser();
                $new_role->role_id = 3;
                $new_role->user_id = $new_user->id;
                $new_role->save();
                
 
            });

    
         
        

    }

    public function ratings()
    {
        return $this->hasMany(ParavetRating::class, 'paravet_id');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
