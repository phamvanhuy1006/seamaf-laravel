<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'quality', 'status'
    ];

    public function scopeCheck($query, $cart)
    {
        $query = $query->where([
            'product_id' => $cart['product_id'],
            'user_id' => $cart['user_id'],
        ]);
        
        return $query;
    }
}
