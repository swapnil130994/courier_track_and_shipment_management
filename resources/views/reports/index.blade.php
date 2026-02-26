@extends('layouts.app')

@section('content')

<h3>Reports Dashboard</h3>

<form method="GET" action="{{ route('reports.index') }}" class="row mb-4">

    <div class="col-md-3">
        <label>From Date</label>
        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
    </div>

    <div class="col-md-3">
        <label>To Date</label>
        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
    </div>

    <div class="col-md-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="">All</option>
            <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
            <option value="In Transit" {{ request('status')=='In Transit'?'selected':'' }}>In Transit</option>
            <option value="Delivered" {{ request('status')=='Delivered'?'selected':'' }}>Delivered</option>
        </select>
    </div>

    <div class="col-md-3 mt-4">
        <button type="submit" class="btn btn-primary">Filter</button>

        <a href="{{ route('reports.export', request()->query()) }}"
           class="btn btn-success">Download CSV</a>

        <a href="{{ route('reports.invoice', request()->query()) }}"
           class="btn btn-danger">Download Invoice PDF</a>
    </div>

</form>

<div class="row">
    <div class="col-md-6">
        <h5>Status Wise Report</h5>
        <canvas id="statusChart"></canvas>
    </div>

    <div class="col-md-6">
        <h5>Date Wise Report</h5>
        <canvas id="dateChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// ================== STATUS CHART ==================
new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($statusCounts->keys()) !!},
        datasets: [{
            label: 'Shipment Count',
            data: {!! json_encode($statusCounts->values()) !!},
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});


// ================== DATE CHART ==================
new Chart(document.getElementById('dateChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($dateCounts->keys()) !!},
        datasets: [{
            label: 'Shipments Per Day',
            data: {!! json_encode($dateCounts->values()) !!},
            fill: false,
            tension: 0.3
        }]
    },
    options: {
        responsive: true
    }
});

</script>

@endsection