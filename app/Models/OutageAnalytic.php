<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutageAnalytic extends Model
{
    use HasFactory;
     protected $table = 'outage_logs';
    protected $fillable = ['device_id', 'status'];
}
