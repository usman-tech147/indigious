<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $appends=['thumbnail_path','free_video_1_path','free_video_2_path'];
    protected $hidden=[
        'stripe_price_id',
        'stripe_price_six_id',
        'paypal_plan_id',
        'paypal_plan_six_id',
        'price_six',
        'price',
    ];

//    public function videos(){
//        return $this->hasMany(Video::class);
//    }
    public function getThumbnailPathAttribute(){
        if($this->thumbnail) {
            return '/uploads/package/' . $this->thumbnail;
        }else{
            return '';
        }
    }
    public function getFreeVideo1PathAttribute(){
        if($this->free_video_1) {
            return '/uploads/package/'.$this->free_video_1;
        }else{
            return '';
        }
    }
    public function getFreeVideo2PathAttribute(){
        if($this->free_video_2){
            return '/uploads/package/'.$this->free_video_2;
        }else{
            return '';
        }
    }
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function users(){
        return $this->belongsToMany(User::class)
        ->withPivot('id','username','password','subscribed_at','updated_at',
        'created_at','subscribed_status','renewal_status','payment_method',
        'subscription_id','expired_at','billing_agreement_id','payment_by',
        'error_message','frequency','interval_count');
    }
    public function categories(){
        return $this->hasMany(Category::class);
    }
    public function payment(){
        return $this->hasOne(Payment::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
