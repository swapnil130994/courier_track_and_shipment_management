<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;

class TrackShipment extends Component
{
    public $awb_number;
    public $shipment;
    public $message;

    public function track()
    {
        $this->validate([
            'awb_number' => 'required'
        ]);

        $this->shipment = Shipment::where('awb_number', $this->awb_number)
            ->with('histories')
            ->first();

        if (!$this->shipment) {
            $this->message = 'No shipment found with this AWB number.';
        } else {
            $this->message = null;
        }
    }

    public function render()
    {
        return view('livewire.track-shipment');
    }
}
