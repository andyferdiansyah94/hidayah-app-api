<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class JasaController extends Controller
{
    public function index()
    {
        try {
            $jasa = Jasa::all();
            return response()->json([
                'message' => 'Get data jasa successfully',
                'data' => $jasa,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data jasa', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'category' => 'required|string|max:255',
            ]);

            $jasa = Jasa::create($request->all());
            return response()->json(['message' => 'Jasa berhasil ditambahkan', 'data' => $jasa], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menambahkan jasa', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $jasa = Jasa::find($id);
            if (!$jasa) {
                return response()->json(['message' => 'Jasa tidak ditemukan'], 404);
            }
            return response()->json(['message' => 'Data jasa ditemukan', 'data' => $jasa], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data jasa', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jasa = Jasa::find($id);
            if (!$jasa) {
                return response()->json(['message' => 'Jasa tidak ditemukan'], 404);
            }

            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric',
                'category' => 'sometimes|required|string|max:255',
            ]);

            $jasa->update($request->all());
            return response()->json(['message' => 'Jasa berhasil diperbarui', 'data' => $jasa], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui jasa', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $jasa = Jasa::find($id);
            if (!$jasa) {
                return response()->json(['message' => 'Jasa tidak ditemukan'], 404);
            }

            $jasa->delete();
            return response()->json(['message' => 'Jasa berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus jasa', 'error' => $e->getMessage()], 500);
        }
    }
}
