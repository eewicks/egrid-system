<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Outage;
use App\Models\Household;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'device_id',
        'barangay',
        'household_name',
        'contact_number',
        'status',
        'last_seen',
        'last_alert_sent'
    ];

    protected $casts = [
        'last_seen'       => 'datetime',
        'last_alert_sent' => 'datetime',
    ];

    // Make derived_status visible in JSON
    protected $appends = ['derived_status', 'display_status'];


    /**
     * Derived status using heartbeat timeout
     * Returns ON or OFF
     */
    public function getDerivedStatusAttribute()
    {
        $thresholdMin = config('services.arduino.heartbeat_timeout_minutes', 1);

        if (!$this->last_seen) {
            return 'OFF';
        }

        $ageSecs = now()->diffInSeconds($this->last_seen);

        return $ageSecs <= ($thresholdMin * 60)
            ? 'ON'
            : 'OFF';
    }


    /**
     * Display status: Active if heartbeat < 60 seconds old
     */
    public function getDisplayStatusAttribute()
    {
        if (!$this->last_seen) return 'Inactive';

        return $this->last_seen->gt(now()->subSeconds(60))
            ? 'Active'
            : 'Inactive';
    }


    /**
     * Device → Household relationship
     */
    public function household()
    {
        return $this->hasOne(Household::class, 'device_pk');
    }


    /**
     * Model-based outage checker (OPTIONAL)
     * Not used in your controller logic but kept clean
     */
    public function checkForOutage()
    {
        // Use derived_status (correct logic)
        if ($this->derived_status === "ON") {
            return; // No outage
        }

        // More than 1 minute → outage event
        if ($this->last_seen && now()->diffInSeconds($this->last_seen) > 60) {

            Outage::create([
                'device_id'    => $this->device_id,  // FIXED (not $this->id)
                'household_id' => $this->household->id ?? null,
                'started_at'   => $this->last_seen,
                'ended_at'     => null,
                'status'       => 'active',
            ]);
        }
    }
}
