<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class JasaController extends Controller
{
    public function index(Request $request)
    {
        $query = Jasa::query();

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
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('updated_at', 'asc');
        }

        $barangs = $query->get();

        return response()->json([
            'message' => 'Get data jasa successfully',
            'data' => $barangs,
        ], 200);
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
