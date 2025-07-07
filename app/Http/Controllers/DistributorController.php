<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;

class DistributorController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Get data Successfully',
            'data' => Distributor::all(), 
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        $distributor = Distributor::create($request->all());
        return response()->json(['message' => 'Distributor created successfully', 'data' => $distributor], 201);
    }

    public function show($id)
    {
        $distributor = Distributor::find($id);

        if (!$distributor) {
            return response()->json(['message' => 'Distributor not found'], 404);
        }

        return response()->json($distributor);
    }

    public function update(Request $request, $id)
    {
        $distributor = Distributor::find($id);

        if (!$distributor) {
            return response()->json(['message' => 'Distributor not found'], 404);
        }

        $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:15',
            'address' => 'string|max:255',
        ]);

        $distributor->update($request->all());
        return response()->json(['message' => 'Distributor updated successfully', 'data' => $distributor]);
    }

    public function destroy($id)
    {
        $distributor = Distributor::find($id);

        if (!$distributor) {
            return response()->json(['message' => 'Distributor not found'], 404);
        }

        $distributor->delete();
        return response()->json(['message' => 'Distributor deleted successfully']);
    }
}
