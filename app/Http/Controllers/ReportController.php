<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Models\TrackHistory;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $shipmentQuery = Shipment::query();

        $from = null;
        $to   = null;

        if ($request->filled('status')) {
            $shipmentQuery->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {

            $from = Carbon::parse($request->from_date)->startOfDay();
            $to   = Carbon::parse($request->to_date)->endOfDay();

            $shipmentQuery->whereHas('histories', function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            });
        }

        $shipments = $shipmentQuery->with('histories')->get();

        $statusCounts = $shipments
            ->groupBy('status')
            ->map(fn($group) => $group->count());

        $historyQuery = $shipmentQuery;//TrackHistory::query();

        if ($from && $to) {
            $historyQuery->whereBetween('created_at', [$from, $to]);
        }

        $dateCounts = $historyQuery
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)
                    ->setTimezone('Asia/Kolkata')
                    ->format('d-m-Y');
            })
            ->map(fn($group) => $group->count())
            ->sortKeys(); // optional sorting by date

        return view('reports.index', compact('statusCounts', 'dateCounts'));
    }
    public function exportCSV(Request $request)
    {
        $query = Shipment::with(['histories' => function ($q) {
            $q->latest();
        }]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereHas('histories', function ($q) use ($request) {
                $q->whereBetween('created_at', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay()
                ]);
            });
        }

        $shipments = $query->get();

        $fileName = "shipments_report.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function() use ($shipments) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'AWB',
                'Sender',
                'Receiver',
                'Status',
                'Current Location',
                'Last Updated'
            ]);

            foreach ($shipments as $shipment) {

                $latestHistory = $shipment->histories->first();

                fputcsv($file, [
                    $shipment->awb_number,
                    $shipment->send_by,
                    $shipment->received_by,
                    $shipment->status,
                    $latestHistory->current_location ?? 'N/A',
                    optional($latestHistory->created_at)
                        ? $latestHistory->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s')
                        : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportInvoicePDF(Request $request)
    {
        $query = Shipment::with(['histories' => function ($q) {
            $q->latest(); // order by created_at DESC
        }]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereHas('histories', function ($q) use ($request) {
                $q->whereBetween('created_at', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay()
                ]);
            });
        }

        $shipments = $query->get();

        $pdf = Pdf::loadView('pdf.invoice-report', compact('shipments'));

        return $pdf->download('invoice_report.pdf');
    }
}
