@extends('layouts.app')

@section('content')

<h3>Dashboard</h3>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white p-3">
            Total Shipments: {{ $total }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            Delivered: {{ $delivered }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white p-3">
            In Transit: {{ $transit }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white p-3">
            Pending: {{ $pending }}
        </div>
    </div>
</div>

<div class="card p-4">
    <canvas id="shipmentChart" height="100"></canvas>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('shipmentChart');

    if (ctx) {

        const labels = {!! json_encode($monthly->pluck('month')) !!};
        const data = {!! json_encode($monthly->pluck('total')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monthly Shipments',
                    data: data,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

});
</script>

@endsection
