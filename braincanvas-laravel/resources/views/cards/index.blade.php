@extends('layouts.app')

@section('judul', 'Daftar Kartu')

@section('isi')
  <div class="top">
    <h1>Kartu BrainCanvas</h1>
    <a class="btn" href="/cards/create">+ Tambah Kartu</a>
  </div>

  @if ($cards->isEmpty())
    <p>Belum ada kartu. Tambahkan kartu pertama.</p>
  @else
    <div class="scroll">
      <table>
        <thead>
          <tr>
            <th>No</th><th>Nama</th><th>Tipe</th><th>Deskripsi</th><th>Status</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cards as $card)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $card->nama }}</td>
              <td>{{ $card->tipe }}</td>
              <td>{{ $card->deskripsi }}</td>
              <td><span class="badge badge-{{ $card->status }}">{{ ucfirst($card->status) }}</span></td>
              <td>
                <div class="aksi">
                  <a class="btn btn-edit" href="/cards/{{ $card->id }}/edit">Edit</a>
                  <form method="POST" action="/cards/{{ $card->id }}"
                        onsubmit="return confirm('Yakin ingin menghapus kartu ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-hapus" type="submit">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
@endsection
