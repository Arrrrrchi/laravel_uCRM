<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Scopes\Subtotal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index ()
    {
        $startDate = '2022-08-01';
        $endDate = '2022-08-10';
        
        $subQuery = Order::betweenDate($startDate, $endDate)
            ->where('status', true)
            ->groupBy('id')
            ->selectRaw('id, sum(subtotal) as totalPerPurchase,
            DATE_FORMAT(created_at, "%Y%m%d") as date');

        $data = DB::table($subQuery)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get();
                
        return Inertia::render('Analysis');
    }
}
