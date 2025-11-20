<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $today = \Carbon\Carbon::today();
        
        $todaysMoney = \App\Models\pipeline::whereDate('created_at', $today)->sum('paid_amount');
        $totalSales = \App\Models\pipeline::sum('paid_amount');
        $todaysUsers = \App\Models\User::whereDate('created_at', $today)->count();
        $totalUsers = \App\Models\User::count();
        $newClients = \App\Models\pipeline::whereDate('created_at', $today)->count();
        
        // Chart Data - Monthly Sales (Last 12 months)
        $monthlySales = \App\Models\pipeline::selectRaw('SUM(paid_amount) as total, MONTH(created_at) as month')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $monthlySalesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[] = $monthlySales[$i] ?? 0;
        }

        return view('livewire.dashboard', compact('todaysMoney', 'totalSales', 'todaysUsers', 'totalUsers', 'newClients', 'monthlySalesData'));
    }
}
