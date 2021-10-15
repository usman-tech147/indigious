<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    public $guarded=[];


//    public function package(){
//        return $this->belongsTo(Package::class);
//    }
    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }

}
