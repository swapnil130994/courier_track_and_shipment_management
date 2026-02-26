<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShipmentMail extends Mailable
{
    use Queueable, SerializesModels;
    public $shipment;
    public $messageText;
    /**
     * Create a new message instance.
     */
    public function __construct($shipment, $messageText)
    {
        $this->shipment = $shipment;
        $this->messageText = $messageText;
    }



    public function build()
    {
        return $this->subject('Shipment Notification - ' . $this->shipment->awb_number)
                    ->view('emails.shipment')
                    ->with([
                        'shipment' => $this->shipment,
                        'messageText' => $this->messageText
                    ]);
    }
}
