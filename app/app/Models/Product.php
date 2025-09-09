<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','description','price','image_url','stock','is_made_to_order','lead_time_hours','active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_made_to_order' => 'boolean',
        'active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

