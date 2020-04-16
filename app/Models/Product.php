<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes; 

    protected $table    = 'products'; 
    protected $guarded  = ['id'];
    protected $fillable = [
        'reference', 
        'name', 
        'description', 
        'quantity', 
        'image', 
        'enable'
    ];

    protected $hidden   = [];
    protected $dates    = ['created_at', 'updated_at', 'deleted_at'];

    public function getImageAttribute()  
    {
      if (filter_var($this->attributes['image'], FILTER_VALIDATE_URL)) {
        return $this->attributes['image']; 
      } else {
        return storage_path('public/img' . $this->attributes['image']);
      }
    }
}
