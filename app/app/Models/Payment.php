<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id','order_id','mp_preference_id','mp_status','amount','is_deposit','payload'
    ];

    protected $casts = [
        'is_deposit' => 'boolean',
        'amount' => 'decimal:2',
        'payload' => 'array',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

