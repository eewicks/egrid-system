<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
        'last_alert_sent'   // ✅ Add this
    ];

    protected $casts = [
        'last_seen'       => 'datetime',
        'last_alert_sent' => 'datetime',   // ✅ Add this
    ];

      public function getDerivedStatusAttribute()
    {
        $thresholdMin = config('services.arduino.heartbeat_timeout_minutes', 1);

        if (!$this->last_seen) {
            return 'OFF';
        }

        $ageSecs = now()->diffInSeconds(Carbon::parse($this->last_seen));

        return $ageSecs <= ($thresholdMin * 60)
            ? 'ON'
            : 'OFF';
    }

    // -----------------------------
    // ✔ Display status (60 sec rule)
    // -----------------------------
    public function getDisplayStatusAttribute()
    {
        if (!$this->last_seen) return 'Inactive';

        // Device is Active only if heartbeat < 60 seconds old
        return $this->last_seen->gt(now()->subSeconds(60))
            ? 'Active'
            : 'Inactive';
    }

    public function household()
    {
        return $this->hasOne(Household::class, 'device_pk');
    }

    public function checkForOutage()
{
    if ($this->status === "ON") {
        return; // No outage
    }

    // If device was OFF for more than 1 minute → Outage
    if ($this->last_seen && now()->diffInSeconds($this->last_seen) > 60) {

        Outage::create([
            'device_id' => $this->id,
            'household_id' => $this->household->id ?? null,
            'started_at' => $this->last_seen,
            'ended_at' => null,
            'status' => 'active',
        ]);
    }
}

}
