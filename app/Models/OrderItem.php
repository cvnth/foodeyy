<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'price', // We save the price at the time of purchase
    ];

    // Relationship: An Item belongs to an Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: An Item links to a Menu Item (for name, image, etc.)
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}