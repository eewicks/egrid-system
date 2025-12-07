<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Outage extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'household_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'week_number',
        'iso_year',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    // FIXED METHOD → REQUIRED BY CONTROLLER / WORKER
    public function closeAt($time)
    {
        $ended = Carbon::parse($time);

        $this->update([
            'ended_at'         => $ended,
            'duration_seconds' => $ended->diffInSeconds($this->started_at),
            'status'           => 'closed',
        ]);
    }
}
