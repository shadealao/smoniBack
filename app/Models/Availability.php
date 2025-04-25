<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id', 'meeting_point_id', 'vehicle_id', 'day_of_week',
        'date', 'start_time', 'end_time', 'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'status' => 'boolean',
        'day_of_week' => 'string',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function meetingPoint()
    {
        return $this->belongsTo(MeetingPoint::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
