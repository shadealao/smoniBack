<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    protected $fillable = [
        'monitor_id', 'meeting_point_id', 'vehicle_id',
        'date', 'start_time', 'end_time', 'status',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => 'boolean',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }

    public function meetingPoint(): BelongsTo
    {
        return $this->belongsTo(MeetingPoint::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
