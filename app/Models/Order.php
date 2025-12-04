<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // These fields are allowed to be mass-assigned
    protected $fillable = [
        'user_id',
        'status',          // pending, completed, etc.
        'payment_method',  // cod, gcash
        'payment_status',
        'delivery_type',   // delivery, pickup
        'total_amount',
        'address',
        'instructions',
    ];

    // Relationship: An Order belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: An Order has many Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}