<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackHistory extends Model
{
    protected $fillable = [
        'shipment_id',
        'status',
        'remarks',
        'current_location'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}
