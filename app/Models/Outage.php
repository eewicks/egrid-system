<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Outage extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_id',
        'device_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'week_number',
        'iso_year',
        'status',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id'); // FK INT
    }

    public function getDurationHumanAttribute()
    {
        if (!$this->duration_seconds) return null;

        return CarbonInterval::seconds($this->duration_seconds)->cascade()->forHumans([
            'parts' => 2,
            'short' => true,
        ]);
    }
}
