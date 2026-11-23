@extends('layouts.app')

@section('judul', $card->exists ? 'Edit Kartu' : 'Tambah Kartu')

@section('isi')
  <h1>{{ $card->exists ? 'Edit Kartu' : 'Tambah Kartu' }}</h1>

  @if ($errors->any())
    <ul class="error">
      @foreach ($errors->all() as $pesan)
        <li>{{ $pesan }}</li>
      @endforeach
    </ul>
  @endif

  <form class="form" method="POST" action="{{ $card->exists ? '/cards/'.$card->id : '/cards' }}">
    @csrf
    @if ($card->exists)
      @method('PUT')
    @endif

    <label for="nama">Nama Kartu</label>
    <input id="nama" name="nama" maxlength="100" required value="{{ old('nama', $card->nama) }}">

    <label for="tipe">Tipe</label>
    <select id="tipe" name="tipe">
      @foreach (['note' => 'Note', 'todo' => 'To-do', 'image' => 'Image'] as $nilai => $label)
        <option value="{{ $nilai }}" @selected(old('tipe', $card->tipe) === $nilai)>{{ $label }}</option>
      @endforeach
    </select>

    <label for="status">Status</label>
    <select id="status" name="status">
      @foreach (['baru' => 'Belum Mulai', 'proses' => 'Dikerjakan', 'selesai' => 'Selesai'] as $nilai => $label)
        <option value="{{ $nilai }}" @selected(old('status', $card->status) === $nilai)>{{ $label }}</option>
      @endforeach
    </select>

    <label for="deskripsi">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="500">{{ old('deskripsi', $card->deskripsi) }}</textarea>

    <div class="aksi">
      <button class="btn" type="submit">{{ $card->exists ? 'Update' : 'Simpan' }}</button>
      <a class="btn btn-batal" href="/cards">Batal</a>
    </div>
  </form>
@endsection
