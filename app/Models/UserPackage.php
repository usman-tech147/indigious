<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserPackage extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $table='package_user';
    public function package(){
        return $this->belongsTo(Package::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
