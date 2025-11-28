<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
