<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=[];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends=['image_path'];
    public function getImagePathAttribute(){
        if($this->profile_picture){
            return '/uploads/profile-pictures/'.$this->profile_picture;
        }
        else{
            return '';
        }
    }
    public function packages(){
        return $this->belongsToMany(Package::class)
            ->withPivot('id','username','password','subscribed_at','updated_at',
                'created_at','subscribed_status','renewal_status','payment_method',
                'subscription_id','expired_at','billing_agreement_id','payment_by',
                'error_message','frequency','interval_count');
    }
    public function beltingRequest(){
        return $this->hasMany(BeltingEvaluation::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    public function subscriptionRequest(){
        return $this->hasMany(ManualSubscriptionRequest::class);
    }
}
