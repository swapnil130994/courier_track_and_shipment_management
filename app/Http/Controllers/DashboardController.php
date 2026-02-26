<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Shipment::count();
        $delivered = Shipment::where('status','Delivered')->count();
        $transit = Shipment::where('status','In Transit')->count();
        $pending = Shipment::where('status','Pending')->count();

        // $monthly = Shipment::selectRaw("MONTH(created_at) as month, count(*) as total")
        //             ->groupBy('month')
        //             ->pluck('total','month');
        $monthly = Shipment::selectRaw("MONTH(created_at) as month_num, MONTHNAME(created_at) as month, count(*) as total")
            ->groupBy('month_num','month')
            ->orderBy('month_num')
            ->get();

        return view('dashboard',compact(
            'total','delivered','transit','pending','monthly'
        ));
    }
}
