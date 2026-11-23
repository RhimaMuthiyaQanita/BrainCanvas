<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('judul', 'Kartu') — BrainCanvas</title>
  <style>
    :root { --navy:#0f1f4d; --blue:#1d6df2; --yellow:#ffd23f; --line:#d9e3f5; --bg:#f5f8ff; --text:#1f2a44; }
    * { box-sizing: border-box; }
    body { margin:0; font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; color:var(--text); background:var(--bg); line-height:1.6; }
    .bar { display:flex; justify-content:space-between; align-items:center; padding:.75rem 1.25rem; background:#fff; border-bottom:1px solid var(--line); }
    .brand { font-size:1.3rem; font-weight:800; color:var(--navy); text-decoration:none; }
    main { width:min(100% - 2rem, 960px); margin:1.5rem auto 3rem; }
    h1 { color:var(--navy); margin:0 0 1rem; font-size:1.6rem; }
    .top { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; }
    .top h1 { margin:0; }
    .scroll { overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:14px; }
    table { width:100%; border-collapse:collapse; min-width:620px; }
    th, td { padding:.65rem .8rem; text-align:left; border-bottom:1px solid var(--line); vertical-align:middle; }
    th { background:#eaf2ff; color:var(--navy); font-size:.9rem; }
    tr:last-child td { border-bottom:0; }
    .btn { display:inline-block; padding:.4rem .9rem; font:inherit; font-weight:700; font-size:.9rem; color:var(--navy); background:var(--yellow); border:0; border-radius:999px; text-decoration:none; cursor:pointer; }
    .btn-edit { background:#eaf2ff; color:var(--blue); }
    .btn-hapus { background:#fdecec; color:#b42318; }
    .btn-batal { background:#fff; border:1.5px solid var(--line); }
    .aksi { display:flex; gap:.4rem; align-items:center; }
    .aksi form { margin:0; }
    .badge { display:inline-block; padding:.1rem .6rem; border-radius:999px; font-size:.8rem; font-weight:700; }
    .badge-baru { background:#fdecec; color:#b42318; }
    .badge-proses { background:#fff6c9; color:#7a5b00; }
    .badge-selesai { background:#e6f7ef; color:#0a6b3d; }
    .alert { padding:.6rem 1rem; background:#e6f7ef; color:#0a6b3d; border-radius:10px; font-weight:600; }
    .error { padding:.6rem 1rem .6rem 2rem; background:#fdecec; color:#b42318; border-radius:10px; }
    .form { display:grid; gap:.4rem; max-width:520px; padding:1.25rem; background:#fff; border:1px solid var(--line); border-radius:14px; }
    .form label { font-weight:600; margin-top:.4rem; }
    .form input, .form select, .form textarea { width:100%; padding:.5rem .7rem; font:inherit; border:1.5px solid var(--line); border-radius:10px; }
    .form .aksi { margin-top:.8rem; }
    a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible { outline:3px solid var(--navy); outline-offset:2px; }
  </style>
</head>
<body>
  <header class="bar">
    <a class="brand" href="/cards">BrainCanvas</a>
    <span>Kelola Kartu</span>
  </header>

  <main>
    @if (session('sukses'))
      <p class="alert" role="status">{{ session('sukses') }}</p>
    @endif

    @yield('isi')
  </main>
</body>
</html>
