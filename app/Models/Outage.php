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
        'status' => 'open', // Default new outages to open
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
        return $this->belongsTo(Device::class, 'device_id', 'device_id');
    }

    public function scopeCurrentWeek($query)
    {
        return $query->where('iso_year', now()->isoWeekYear())
                     ->where('week_number', now()->isoWeek());
    }

    public function closeAt(\DateTimeInterface $endedAt): void
    {
        $ended = Carbon::instance($endedAt);
        $duration = $this->started_at ? $ended->diffInSeconds($this->started_at) : null;

        $this->forceFill([
            'ended_at' => $ended,
            'duration_seconds' => $duration,
            'status' => 'closed',
        ])->save();
    }

    public function getDurationMinutesAttribute(): ?int
    {
        return $this->duration_seconds ? (int) round($this->duration_seconds / 60) : null;
    }

    public function getDurationHumanAttribute(): ?string
    {
        if (!$this->duration_seconds) return null;

        return CarbonInterval::seconds($this->duration_seconds)
            ->cascade()
            ->forHumans([
                'parts' => 2,
                'short' => true,
            ]);
    }
}
