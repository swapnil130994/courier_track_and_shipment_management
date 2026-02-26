<div>
    <h3>Track Shipment</h3>

    <div class="row">
        <div class="col-md-6">
            <input type="text" id="awb_number" class="form-control"
                   placeholder="Enter AWB Number">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" onclick="trackShipment()">Track</button>
        </div>
    </div>

    <br>

    <div id="trackingResult"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/utc.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/timezone.js"></script>
<script>
dayjs.extend(dayjs_plugin_utc);
dayjs.extend(dayjs_plugin_timezone);
</script>
<script>
function trackShipment() {
    let awb = document.getElementById('awb_number').value;

    if (awb.trim() === '') {
        alert('Please enter AWB number');
        return;
    }

    let resultDiv = document.getElementById('trackingResult');
    resultDiv.innerHTML = 'Loading...';

    fetch("{{ route('track.result') }}?awb_number=" + awb)
        .then(response => response.json())
        .then(data => {
            if (!data.status) {
                resultDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                return;
            }

            let shipment = data.data;

            let html = `
                <div class="card">
                    <div class="card-body">
                        <h5>Shipment Details</h5>
                        <p><strong>AWB:</strong> ${shipment.awb_number}</p>
                        <p><strong>Sender:</strong> ${shipment.send_by}</p>
                        <p><strong>Receiver:</strong> ${shipment.received_by}</p>
                        <p><strong>Origin:</strong> ${shipment.origin}</p>
                        <p><strong>Destination:</strong> ${shipment.destination}</p>
                        <p><strong>Status:</strong> ${shipment.status}</p>
                    </div>
                </div>
            `;

            if (shipment.histories && shipment.histories.length > 0) {
                html += `
                    <h5 class="mt-4">Tracking History</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Location</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                shipment.histories.forEach(history => {
                    html += `
                        <tr>
                            <td>${dayjs(history.created_at)
                            .tz('Asia/Kolkata')
                            .format('DD-MM-YYYY HH:mm:ss')}</td>
                            <td>${history.status}</td>
                            <td>${history.current_location  ?? ''}</td>
                            <td>${history.remarks ?? ''}</td>
                        </tr>
                    `;
                });

                html += `</tbody></table>`;
            }

            resultDiv.innerHTML = html;
        })
        .catch(error => {
            resultDiv.innerHTML = `<div class="alert alert-danger">Something went wrong</div>`;
        });
}
</script>
