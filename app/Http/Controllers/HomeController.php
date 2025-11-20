<?php

namespace App\Http\Controllers;

use App\Models\HInvoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $params['totalform'] = HInvoice::count();
        $params['hariini'] = HInvoice::whereDate('InvoiceDate', now()->toDateString())->orWhereDate('SJDate', now()->toDateString())->count();
        $params['mingguini'] = HInvoice::whereBetween('InvoiceDate', [now()->startOfWeek(), now()->endOfWeek()])->orWhereBetween('SJDate', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $params['bulanini'] = HInvoice::whereMonth('InvoiceDate', now()->month)->orWhereMonth('SJDate', now()->month)->count();
        $params['tahunini'] = HInvoice::whereYear('InvoiceDate', now()->year)->orWhereYear('SJDate', now()->year)->count();
        return view('home', $params);
    }
}
