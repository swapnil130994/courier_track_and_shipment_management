@extends('layouts.app')

@section('content')

<h3>Edit Shipment</h3>

<form method="POST" action="{{ route('shipments.update', $shipment->id) }}">
@csrf
@method('PUT')

<div class="mb-2">
    <label>Sender Name</label>
    <input type="text" name="send_by" value="{{ $shipment->send_by }}" class="form-control" placeholder="Enter sender name">
</div>

<div class="mb-2">
    <label>Receiver Name</label>
    <input type="text" name="received_by" value="{{ $shipment->received_by }}" class="form-control" placeholder="Enter receiver name">
</div>

<div class="mb-2">
    <label>Origin</label>
    <input type="text" name="origin" value="{{ $shipment->origin }}" class="form-control" placeholder="Enter origin">
</div>

<div class="mb-2">
    <label>Current Location</label>
    <input type="text" name="current_location" value="{{ $track->current_location }}" class="form-control" placeholder="Enter current location">
</div>

<div class="mb-2">
    <label>Destination</label>
    <input type="text" name="destination" value="{{ $shipment->destination }}" class="form-control" placeholder="Enter destination">
</div>

<div class="mb-2">
    <label>Weight</label>
    <input type="number" name="weight" value="{{ $shipment->weight }}" class="form-control" placeholder="Enter weight">
</div>

<div class="mb-2">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="Pending" {{ $shipment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="In Transit" {{ $shipment->status == 'In Transit' ? 'selected' : '' }}>In Transit</option>
        <option value="Delivered" {{ $shipment->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
    </select>
</div>

<button class="btn btn-primary">Update</button>

</form>

@endsection
