<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('sort')) {
            switch ($request->input('sort')) {
                case 'az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'za':
                    $query->orderBy('name', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'latest':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    $query->orderBy('updated_at', 'desc');
                    break;
            }
        }else {
            $query->orderBy('updated_at', 'asc');
        }

        $barangs = $query->get();


        return response()->json([
            'message' => 'Get data barang successfully',
            'data' => $barangs,
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
