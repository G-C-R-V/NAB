<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'weekday', 'start_time', 'end_time'
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

