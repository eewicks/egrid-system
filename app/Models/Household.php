<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_pk',
        'device_id',
        'name',
        'location',
        'contact_number',
        'last_status',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_pk');
    }

    public function outages()
    {
        return $this->hasMany(Outage::class);
    }

    public function openOutage()
    {
        return $this->hasOne(Outage::class)->whereNull('ended_at');
    }

    public function getDisplayLabelAttribute(): string
    {
        $location = $this->location ? ' – '.$this->location : '';
        return ($this->name ?: 'Household '.$this->device_id) . $location;
    }
}

