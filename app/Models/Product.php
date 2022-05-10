<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'category_id', 'price', 'is_top', 'on_sale'
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts', 'product_id', 'user_id');
    }

    public function scopeSearch($query, $keyword)
    {
        if (isset($keyword)) {
            $query->where('name', 'LIKE', '%'. $keyword.'%')->orWhere('code', 'LIKE', '%'.$keyword.'%');
        }
        return $query;
    }
}
