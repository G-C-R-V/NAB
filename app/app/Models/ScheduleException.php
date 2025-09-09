<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleException extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'date', 'is_closed', 'start_time', 'end_time', 'note'
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

