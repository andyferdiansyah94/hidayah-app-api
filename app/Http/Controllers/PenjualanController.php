<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\Jasa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_barang' => 'nullable|array',
                'nama_barang.*.id_barang' => 'required|exists:barang,id',
                'nama_barang.*.nama_barang' => 'required|string',
                'nama_barang.*.kuantitas' => 'required|integer|min:1',
                'nama_jasa' => 'nullable|array',
                'nama_jasa.*.id_jasa' => 'required|exists:jasas,id',
                'nama_jasa.*.nama_jasa' => 'required|string',
                'nama_jasa.*.kuantitas' => 'required|integer|min:1',
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'harga' => 'required|integer|min:1',
            ]);

            foreach ($validated['nama_barang'] as $barang) {
                $barangModel = Barang::find($barang['id_barang']);
                if ($barangModel->quantity < $barang['kuantitas']) {
                    return response()->json([
                        'error' => "Stok barang {$barangModel->nama_barang} tidak mencukupi."
                    ], 400);
                }
                $barangModel->quantity -= $barang['kuantitas'];
                $barangModel->save();
            }

            $kuantitas = collect($validated['nama_barang'])->sum('kuantitas');

            \Log::info('Data Jasa:', $validated['nama_jasa']);

            
            $penjualan = Penjualan::create([
                'data_barang' => $validated['nama_barang'], 
                'data_jasa' => $validated['nama_jasa'],    
                'pelanggan_id' => $validated['pelanggan_id'],
                'kuantitas' => $kuantitas,
                'harga' => $validated['harga'],
            ]);                    

            return response()->json([
                'message' => 'Penjualan berhasil disimpan.',
                'penjualan' => $penjualan,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $penjualans = Penjualan::all();
        return response()->json($penjualans);
    }

    public function show($id)
    {
        $penjualan = Penjualan::find($id);
        if (!$penjualan) {
            return response()->json(['message' => 'Penjualan tidak ditemukan'], 404);
        }
        return response()->json($penjualan);
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);
        if (!$penjualan) {
            return response()->json(['message' => 'Penjualan tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama_barang' => 'required|array',
            'nama_barang.*.id_barang' => 'required|exists:barang,id',
            'nama_barang.*.kuantitas' => 'required|integer|min:1',
            'pelanggan_id' => 'required|exists:user_sistems,id',
            'harga' => 'required|integer|min:1',
        ]);

        foreach ($penjualan->nama_barang as $barangLama) {
            $barangModel = Barang::find($barangLama['id_barang']);
            if ($barangModel) {
                $barangModel->quantity += $barangLama['kuantitas'];
                $barangModel->save();
            }
        }

        foreach ($validated['nama_barang'] as $barangBaru) {
            $barangModel = Barang::find($barangBaru['id_barang']);
            if ($barangModel->quantity < $barangBaru['kuantitas']) {
                return response()->json([
                    'error' => "Stok barang {$barangModel->nama} tidak mencukupi."
                ], 400);
            }
            $barangModel->quantity -= $barangBaru['kuantitas'];
            $barangModel->save();
        }

        $penjualan->update([
            'nama_barang' => $validated['nama_barang'],
            'pelanggan_id' => $validated['pelanggan_id'],
            'kuantitas' => array_sum(array_column($validated['nama_barang'], 'kuantitas')),
            'harga' => $validated['harga'],
        ]);

        return response()->json([
            'message' => 'Penjualan berhasil diperbarui.',
            'penjualan' => $penjualan,
        ], 200);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        if (!$penjualan) {
            return response()->json(['message' => 'Penjualan tidak ditemukan'], 404);
        }
        $penjualan->delete();
        return response()->json(['message' => 'Penjualan berhasil dihapus'], 200);
    }

    public function getTodaySales()
    {
        $todaySales = Penjualan::with('pelanggan')
        ->whereDate('created_at', Carbon::today())
        ->get();
        if ($todaySales->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data penjualan hari ini.',
                'data' => []
            ], 200);
        }
        
        $penjualanData = $todaySales->map(function ($penjualan) {
            $namaBarang = collect($penjualan->data_barang)->map(function ($item) {
                $barang = Barang::find($item['id_barang']);
                return [
                    'id_barang' => $item['id_barang'],
                    'nama_barang' => $item['nama_barang'],
                    'kuantitas' => $item['kuantitas']
                ];
            });
        
            $namaJasa = collect($penjualan->data_jasa)->map(function ($item) {
                $jasa = Jasa::find($item['id_jasa']);
                return [
                    'id_jasa' => $item['id_jasa'],
                    'nama_barang' => $item['nama_jasa'],
                    'kuantitas' => $item['kuantitas'] 
                ];
            });
        
            $namaBarangJasa = $namaBarang->merge($namaJasa);
        
            return [
                'id' => $penjualan->id,
                'nama_barang' => $namaBarangJasa,
                'pelanggan_id' => $penjualan->pelanggan_id,
                'pelanggan_name' => $penjualan->pelanggan->name,
                'kuantitas' => $penjualan->kuantitas,
                'harga' => $penjualan->harga,
                'created_at' => $penjualan->created_at,
                'updated_at' => $penjualan->updated_at,
            ];
        });
        
        return response()->json([
            'message' => 'Data penjualan hari ini berhasil diambil.',
            'data' => $penjualanData,
        ]);        
    }

    public function getSalesByMonth(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2000',
        ]);

        $bulan = $validated['bulan'];
        $tahun = $validated['tahun'];

        $penjualans = Penjualan::with('pelanggan')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();

        if ($penjualans->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data penjualan untuk bulan dan tahun yang diminta.',
                // 'data' => []
            ], 200);
        }

        $penjualanData = $penjualans->map(function ($penjualan) {
            $namaBarang = collect($penjualan->data_barang)->map(function ($item) {
                $barang = Barang::find($item['id_barang']);
                return [
                    'id_barang' => $item['id_barang'],
                    'nama_barang' => $item['nama_barang'],
                    'kuantitas' => $item['kuantitas']
                ];
            });

            // Menyusun data jasa
            $namaJasa = collect($penjualan->data_jasa)->map(function ($item) {
                $jasa = Jasa::find($item['id_jasa']);
                return [
                    'id_jasa' => $item['id_jasa'],
                    'nama_barang' => $item['nama_jasa'],
                    'kuantitas' => $item['kuantitas']
                ];
            });

            $namaBarangJasa = $namaBarang->merge($namaJasa);

            return [
                'id' => $penjualan->id,
                'nama_barang' => $namaBarangJasa,
                'pelanggan_id' => $penjualan->pelanggan_id,
                'pelanggan_name' => $penjualan->pelanggan->name,
                'kuantitas' => $penjualan->kuantitas,
                'harga' => $penjualan->harga,
                'created_at' => $penjualan->created_at,
                'updated_at' => $penjualan->updated_at,
            ];
        });

        return response()->json([
            'message' => 'Data penjualan berhasil diambil.',
            'data' => $penjualanData,
        ]);
    }
}
