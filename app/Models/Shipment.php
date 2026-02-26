<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'awb_number',
        'origin',
        'destination',
        'weight',
        'send_by',
        'received_by',
        'received_by_email',
        'status'
    ];

    public function latestTrack()
    {
        return $this->hasOne(TrackHistory::class)->latestOfMany();
    }

    public function histories()
    {
        return $this->hasMany(TrackHistory::class, 'shipment_id');
    }
}
