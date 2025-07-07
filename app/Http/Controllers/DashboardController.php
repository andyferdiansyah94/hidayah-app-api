<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getCounts()
    {
        $counts = [
            'karyawan' => DB::table('employees')->count(),
            'barang' => DB::table('barang')->count(),
            'distributor' => DB::table('distributors')->count(),
            'jasa' => DB::table('jasas')->count(),
            'pelanggan' => DB::table('pelanggans')->count(),
            'laporan' => DB::table('penjualans')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'penjualan' => 0, 
            'kategori' => DB::table('kategori')->count(),
        ];

        return response()->json($counts);
    }
}

