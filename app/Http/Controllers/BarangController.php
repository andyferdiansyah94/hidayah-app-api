<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Get data barang successfully',
            'data' => Barang::all(),
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
        ]);

        $barang = Barang::create($validatedData);

        return response()->json(['message' => 'Barang created successfully', 'data' => $barang], 201);
    }

    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'category' => 'sometimes|required|string|max:255',
        ]);

        $barang->update($validatedData);

        return response()->json(['message' => 'Barang updated successfully', 'data' => $barang]);
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json(['message' => 'Barang deleted successfully']);
    }
}
