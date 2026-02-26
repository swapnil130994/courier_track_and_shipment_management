<h2>Shipment Invoice Report</h2>

<table border="1" width="100%" cellpadding="5">
    <thead>
        <tr>
            <th>AWB</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Current Location</th>
            <th>Last Updated</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shipments as $shipment)

            @php
                $latestHistory = $shipment->histories->first();
            @endphp

            <tr>
                <td>{{ $shipment->awb_number }}</td>
                <td>{{ $shipment->send_by }}</td>
                <td>{{ $shipment->received_by }}</td>
                <td>{{ $latestHistory->current_location ?? 'N/A' }}</td>
                <td>
                    {{ optional($latestHistory->created_at)
                        ? $latestHistory->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s')
                        : 'N/A'
                    }}
                </td>
                <td>{{ $shipment->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>