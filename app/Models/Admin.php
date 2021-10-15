<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $guarded=[];
    protected $hidden=['password','verification_code','verification_code_time'];

    public function packages(){
        return $this->hasMany(Package::class);
    }
}
