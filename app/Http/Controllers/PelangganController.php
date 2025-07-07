<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Success get data',
            'data' => Pelanggan::all(),
        ], 200);
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan not found'], 404);
        }

        return response()->json([
            'message' => 'Success get data',
            'data' => $pelanggan,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $pelanggan = Pelanggan::create($request->all());

        return response()->json([
            'message' => 'Success add Customer',
            'data' => $pelanggan, 
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $pelanggan->update($request->all());

        return response()->json([
            'message' => 'Success update data',
            'data' => $pelanggan,
        ], 200);
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan not found'], 404);
        }

        $pelanggan->delete();

        return response()->json(['message' => 'Pelanggan deleted successfully'], 200);
    }
}
