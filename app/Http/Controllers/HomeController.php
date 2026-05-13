<?php

namespace App\Http\Controllers;

use App\Models\HInvoice;
use App\Models\MProduct;
use App\Models\MSingle;
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
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $params['singlecookies'] = MSingle::with('pricesinglecustomer')->where('IsDel', 0)->get();
        $params['singlecategories'] = [
            'Bulat 250gr' => ['bulat-250gr', '250gr'],
            'Bulat 300gr' => ['bulat-300gr', '300gr'],
            'Bulat 500gr' => ['bulat-500gr', '500gr'],
            'Tabung 400ml' => ['tabung-400ml', '400ml'],
            'Tabung 700ml' => ['tabung-700ml', '700ml'],
            'Kiloan' => ['kiloan', 'Kiloan'],
        ];

        $params['paketcookies'] = MProduct::with('price')->where('IsShowPaket', 1)->get();
        return view('welcome', $params);
    }

    public function dashboard()
    {
        $params['totalform'] = HInvoice::count();
        $params['hariini'] = HInvoice::whereDate('InvoiceDate', now()->toDateString())->orWhereDate('SJDate', now()->toDateString())->count();
        $params['mingguini'] = HInvoice::whereBetween('InvoiceDate', [now()->startOfWeek(), now()->endOfWeek()])->orWhereBetween('SJDate', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $params['bulanini'] = HInvoice::whereMonth('InvoiceDate', now()->month)->orWhereMonth('SJDate', now()->month)->count();
        $params['tahunini'] = HInvoice::whereYear('InvoiceDate', now()->year)->orWhereYear('SJDate', now()->year)->count();
        return view('home', $params);
    }
}
