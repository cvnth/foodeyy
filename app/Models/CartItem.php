<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'quantity',
    ];

    // Relationship: CartItem belongs to Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relationship: CartItem belongs to MenuItem
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}