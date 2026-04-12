<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category_id',
        'demo_url',
        'thumbnail_path',
        'script_file_path',
        'download_limit',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function screenshots()
    {
        return $this->hasMany(ProductScreenshot::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
