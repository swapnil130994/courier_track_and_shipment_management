@extends('layouts.app')

@section('content')

<h3>Shipments</h3>

<!-- Filter Form (Responsive) -->
<form method="GET" action="{{ route('shipments.index') }}" class="mb-3">
    <div class="row g-2">
        <div class="col-12 col-md-4">
            <input type="text" name="awb_number" class="form-control"
                   placeholder="Search by AWB"
                   value="{{ request('awb_number') }}">
        </div>

        <div class="col-12 col-md-4">
            <select name="status" class="form-control">
                <option value="">-- Select Status --</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Transit" {{ request('status') == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
            </select>
        </div>

        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('shipments.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>

<a href="{{ route('shipments.create') }}" class="btn btn-primary mb-3">
    Add Shipment
</a>

<!-- Toast Notification -->
@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif

<!-- Responsive Table -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>AWB</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Current Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
            <tr>
                <td>{{ $shipment->awb_number }}</td>
                <td>{{ $shipment->send_by }}</td>
                <td>{{ $shipment->received_by }}</td>
                <td>{{ $shipment->origin }}</td>
                <td>{{ $shipment->destination }}</td>
                <td>{{ $shipment->latestTrack->current_location }}</td>
                <td>{{ $shipment->status }}</td>
                <td>
                    <a href="{{ route('shipments.edit',$shipment->id) }}"
                       class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('shipments.destroy',$shipment->id) }}"
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $shipments->appends(request()->query())->links() }}

<!-- Toast Auto Show Script -->
@if(session('success'))
<script>
    window.onload = function() {
        var toastEl = document.getElementById('successToast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    };
</script>
@endif

@endsection