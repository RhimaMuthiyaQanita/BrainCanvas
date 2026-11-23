<?php

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Aturan isian form (validasi sederhana; pembahasan lengkap di Pertemuan 10) */
function aturanKartu(Request $request): array
{
    return $request->validate([
        'nama'      => 'required|max:100',
        'deskripsi' => 'nullable|max:500',
        'tipe'      => 'required|in:note,todo,image',
        'status'    => 'required|in:baru,proses,selesai',
    ]);
}

Route::get('/', fn () => redirect('/cards'));

// READ: tampilkan semua kartu dari database
Route::get('/cards', function () {
    return view('cards.index', ['cards' => Card::orderBy('id')->get()]);
});

// CREATE: form tambah kartu
Route::get('/cards/create', function () {
    return view('cards.form', ['card' => new Card()]);
});

// CREATE: simpan kartu baru ke database
Route::post('/cards', function (Request $request) {
    Card::create(aturanKartu($request));
    return redirect('/cards')->with('sukses', 'Kartu berhasil ditambahkan.');
});

// UPDATE: form edit (data kartu diambil dengan Card::find lewat route model binding)
Route::get('/cards/{card}/edit', function (Card $card) {
    return view('cards.form', ['card' => $card]);
});

// UPDATE: simpan perubahan
Route::put('/cards/{card}', function (Request $request, Card $card) {
    $card->update(aturanKartu($request));
    return redirect('/cards')->with('sukses', 'Kartu berhasil diperbarui.');
});

// DELETE: hapus kartu dari database
Route::delete('/cards/{card}', function (Card $card) {
    $card->delete();
    return redirect('/cards')->with('sukses', 'Kartu berhasil dihapus.');
});
