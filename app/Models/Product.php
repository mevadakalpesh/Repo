<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
  use HasFactory;
  
  protected $guarded = [];
  public static $mediapath = "/image";
  protected $appends = ['fullimage'];
  
  
  public function getFullimageAttribute() {
    $image = config('constant.default_image');
    if (!blank($this->image)) {
      $image = Storage::disk(config('constant.storage_type'))->url(self::$mediapath.'/'.$this->image);
    }
    return $this->attributes['fullimage'] = $image;
  }

}