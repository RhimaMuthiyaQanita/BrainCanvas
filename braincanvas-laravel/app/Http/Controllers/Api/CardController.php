<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * Week 10 (Task 03): RESTful API untuk kartu. Semua response berupa JSON
 * dengan status code yang sesuai (200, 201, 404, 422).
 */
class CardController extends Controller
{
    // GET /api/cards -> 200
    public function index(): JsonResponse
    {
        return response()->json(['data' => Card::orderBy('id')->get()]);
    }

    // POST /api/cards -> 201, atau 422 jika data tidak valid
    public function store(Request $request): JsonResponse
    {
        $validator = $this->validasi($request);

        if ($validator->fails()) {
            return $this->gagal($validator);
        }

        $card = Card::create($validator->validated());

        return response()->json([
            'message' => 'Kartu berhasil dibuat.',
            'data'    => $card->refresh(),
        ], 201);
    }

    // GET /api/cards/{id} -> 200, atau 404
    public function show($id): JsonResponse
    {
        $card = Card::find($id);

        if (! $card) {
            return $this->tidakDitemukan();
        }

        return response()->json(['data' => $card]);
    }

    // PUT/PATCH /api/cards/{id} -> 200, 404, atau 422
    public function update(Request $request, $id): JsonResponse
    {
        $card = Card::find($id);

        if (! $card) {
            return $this->tidakDitemukan();
        }

        $validator = $this->validasi($request);

        if ($validator->fails()) {
            return $this->gagal($validator);
        }

        $card->update($validator->validated());

        return response()->json([
            'message' => 'Kartu berhasil diperbarui.',
            'data'    => $card,
        ]);
    }

    // DELETE /api/cards/{id} -> 200, atau 404
    public function destroy($id): JsonResponse
    {
        $card = Card::find($id);

        if (! $card) {
            return $this->tidakDitemukan();
        }

        $card->delete();

        return response()->json(['message' => 'Kartu berhasil dihapus.']);
    }

    /* ---------- Bantuan ---------- */

    // PATCH boleh mengirim sebagian field, POST dan PUT wajib mengirim nama
    private function validasi(Request $request)
    {
        $nama = $request->isMethod('PATCH')
            ? 'sometimes|required|string|max:100'
            : 'required|string|max:100';

        return Validator::make($request->all(), [
            'nama'      => $nama,
            'deskripsi' => 'nullable|string|max:500',
            'tipe'      => 'sometimes|in:note,todo,image',
            'status'    => 'sometimes|in:baru,proses,selesai',
            'x'         => 'sometimes|integer|min:0',
            'y'         => 'sometimes|integer|min:0',
        ], [
            'nama.required' => 'Nama kartu wajib diisi.',
            'nama.max'      => 'Nama kartu maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            'tipe.in'       => 'Tipe kartu harus note, todo, atau image.',
            'status.in'     => 'Status harus baru, proses, atau selesai.',
        ]);
    }

    private function gagal($validator): JsonResponse
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    private function tidakDitemukan(): JsonResponse
    {
        return response()->json(['message' => 'Kartu tidak ditemukan.'], 404);
    }
}
