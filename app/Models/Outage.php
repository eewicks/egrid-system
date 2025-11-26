<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function scopeCurrentWeek($query)
    {
        return $query->where('iso_year', now()->isoWeekYear())
            ->where('week_number', now()->isoWeek());
    }

    public function closeAt(\DateTimeInterface $endedAt): void
    {
        $ended = \Illuminate\Support\Carbon::instance($endedAt);
        $duration = null;
        if ($this->started_at) {
            $duration = $ended->diffInSeconds($this->started_at);
        }

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
        if (!$this->duration_seconds) {
            return null;
        }

        return CarbonInterval::seconds($this->duration_seconds)->cascade()->forHumans([
            'parts' => 2,
            'short' => true,
        ]);
    }
}

