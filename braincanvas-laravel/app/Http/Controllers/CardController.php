<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

/*
 * Week 10 (Task 01 + 02): CRUD dipindah dari routes/web.php ke Controller,
 * lengkap dengan Request dan Validation.
 */
class CardController extends Controller
{
    // READ: tampilkan semua kartu
    public function index()
    {
        $cards = Card::orderBy('id')->get();

        return view('cards.index', compact('cards'));
    }

    // FORM tambah kartu
    public function create()
    {
        return view('cards.form', ['card' => new Card()]);
    }

    // CREATE: validasi input, lalu simpan ke database
    public function store(Request $request)
    {
        $data = $request->validate($this->aturan(), $this->pesan());

        Card::create($data);

        return redirect('/cards')->with('sukses', 'Kartu berhasil ditambahkan.');
    }

    // FORM edit kartu
    public function edit($id)
    {
        $card = Card::findOrFail($id);

        return view('cards.form', compact('card'));
    }

    // UPDATE: validasi input, lalu perbarui data
    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);
        $data = $request->validate($this->aturan(), $this->pesan());

        $card->update($data);

        return redirect('/cards')->with('sukses', 'Kartu berhasil diperbarui.');
    }

    // DELETE: hapus kartu
    public function destroy($id)
    {
        Card::findOrFail($id)->delete();

        return redirect('/cards')->with('sukses', 'Kartu berhasil dihapus.');
    }

    // Aturan validasi
    private function aturan(): array
    {
        return [
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'tipe'      => 'sometimes|in:note,todo,image',
            'status'    => 'sometimes|in:baru,proses,selesai',
        ];
    }

    // Pesan kesalahan dalam bahasa Indonesia
    private function pesan(): array
    {
        return [
            'nama.required'  => 'Nama kartu wajib diisi.',
            'nama.max'       => 'Nama kartu maksimal 100 karakter.',
            'deskripsi.max'  => 'Deskripsi maksimal 500 karakter.',
            'tipe.in'        => 'Tipe kartu harus note, todo, atau image.',
            'status.in'      => 'Status harus baru, proses, atau selesai.',
        ];
    }
}
