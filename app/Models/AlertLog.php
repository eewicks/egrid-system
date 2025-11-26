<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'device_id',
        'barangay', 
        'message',
        'alert_type'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the device that this alert belongs to
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'device_id');
    }
}
