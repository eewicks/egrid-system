<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = 'devices';
    protected $fillable = ['device_id','barangay','household_name','contact_number','status','last_seen'];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function getDisplayStatusAttribute()
    {
        if (!$this->last_seen) return 'Inactive';
        return $this->last_seen->gt(now()->subMinutes(10)) ? 'Active' : 'Inactive';
    }

    public function household()
    {
        return $this->hasOne(Household::class, 'device_pk');
    }
}
