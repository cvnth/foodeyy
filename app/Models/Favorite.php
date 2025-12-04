<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // THIS LINE IS CRITICAL. If it is missing, nothing saves.
    protected $fillable = [
        'user_id', 
        'menu_item_id'
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}