@extends('layouts.app')

@section('content')

<h3>Add Shipment</h3>

<form method="POST" action="{{ route('shipments.store') }}">
@csrf

<input type="text" name="send_by" class="form-control mb-2" placeholder="Sender Name">
<input type="text" name="received_by" class="form-control mb-2" placeholder="Receiver Name">
<input type="email" name="received_by_email" class="form-control mb-2" placeholder="Receiver Email">
<input type="text" name="origin" class="form-control mb-2" placeholder="Origin">
<input type="text" name="destination" class="form-control mb-2" placeholder="Destination">
<input type="number" name="weight" class="form-control mb-2" placeholder="Weight">

<button class="btn btn-success">Save</button>

</form>

@endsection
