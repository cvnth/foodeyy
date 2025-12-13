<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'menu_item_id'];

    // --- ADD THIS FUNCTION ---
    public function menuItem()
    {
        // This tells Laravel that 'menu_item_id' links to the MenuItem model
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}