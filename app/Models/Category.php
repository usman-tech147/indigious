<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $appends=['image_path'];
    public function getImagePathAttribute(){
        return '/uploads/category/'.$this->image;
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }
    public function subCategories(){
        return $this->hasMany(SubCategory::class);
    }
}
