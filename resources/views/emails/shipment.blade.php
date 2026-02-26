<!DOCTYPE html>
<html>
<head>
    <title>Shipment Notification</title>
</head>
<body>
    <h2>Shipment Update</h2>
    <p>{{ $messageText }}</p>

    <h3>Shipment Details</h3>
    <p><strong>AWB:</strong> {{ $shipment->awb_number }}</p>
    <p><strong>Status:</strong> {{ $shipment->status }}</p>
    <p><strong>Origin:</strong> {{ $shipment->origin }}</p>
    <p><strong>Destination:</strong> {{ $shipment->destination }}</p>
</body>
</html>
