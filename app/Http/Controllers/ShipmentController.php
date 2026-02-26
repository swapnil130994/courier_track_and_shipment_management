<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Mail\ShipmentMail;
use Illuminate\Support\Facades\Mail;
use App\Models\TrackHistory;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('latestTrack');

        if ($request->filled('awb_number')) {
            $query->where('awb_number', 'like', '%' . $request->awb_number . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->latest()->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        return view('shipments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'send_by' => 'required',
            'received_by' => 'required',
            'received_by_email' => 'required|email',
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric'
        ]);

        $shipment = Shipment::create([
            'awb_number' => 'AWB'.rand(100000,999999),
            'send_by' => $request->send_by,
            'received_by' => $request->received_by,
            'received_by_email' => $request->received_by_email,
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'status' => 'Pending'
        ]);

        // Insert new history entry
        TrackHistory::create([
            'shipment_id' => $shipment->id,
            'status' => 'Pending',
            'current_location' => $request->origin,
            'remarks' => 'Status updated to Pending'
        ]);
        // Send Email Notification
        Mail::to($request->received_by_email)->send(new ShipmentMail(
            $shipment,
            "Your shipment has been created successfully."
        ));

        return redirect()->route('shipments.index')
            ->with('success','Shipment Created Successfully');
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        $track = TrackHistory::where('shipment_id', $id)
                ->latest()
                ->first();
        return view('shipments.edit', compact('shipment','track'));
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $shipment->update($request->only([
            'send_by','received_by','received_by_email','origin','destination','weight','status'
        ]));
        // Insert new history entry
        TrackHistory::create([
            'shipment_id' => $shipment->id,
            'status' => $request->status,
            'current_location' => $request->current_location,
            'remarks' => 'Status updated to ' . $request->status
        ]);
        // Send status update email
        if ($request->status == 'Delivered') {
            Mail::to($shipment->received_by_email)->send(new ShipmentMail(
                $shipment,
                "Your shipment has been delivered successfully."
            ));
        }
        else
        {
            Mail::to($shipment->received_by_email)->send(new ShipmentMail(
                $shipment,
                "Your shipment status has been updated to: " . $shipment->status
            ));
        }

        return redirect()->route('shipments.index')
            ->with('success','Shipment Updated Successfully');
    }

    public function show($awb)
    {
        return Shipment::where('awb',$awb)
                ->with('histories')
                ->firstOrFail();
    }

    public function trackForm()
    {
        return view('track');
    }

    public function trackResult(Request $request)
    {
        $request->validate([
            'awb_number' => 'required'
        ]);

        $shipment = Shipment::where('awb_number', $request->awb_number)
                    ->with('histories')
                    ->first();

        if (!$shipment) {
            return response()->json([
                'status' => false,
                'message' => 'No shipment found with this AWB number.'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $shipment
        ]);
    }

    public function destroy($id)
    {
        Shipment::findOrFail($id)->delete();
        return redirect()->route('shipments.index')
            ->with('success','Shipment Deleted Successfully');
    }
}
