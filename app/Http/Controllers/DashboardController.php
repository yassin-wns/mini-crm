<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with customer metrics.
     */
    public function index(): View
    {
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'Active')->count();
        $inactiveCustomers = Customer::where('status', 'Inactive')->count();
        $leadCustomers = Customer::where('status', 'Lead')->count();

        $recentCustomers = Customer::with('creator')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'pageTitle' => 'Dashboard',
            'activeNav' => 'dashboard',
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'inactiveCustomers' => $inactiveCustomers,
            'leadCustomers' => $leadCustomers,
            'recentCustomers' => $recentCustomers,
        ]);
    }
}
