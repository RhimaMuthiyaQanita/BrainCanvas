<?php
/* =========================================================
   BrainCanvas — prioritas.php (Week 5)
   PHP procedural, server-side rendering.
   Task 01: variabel PHP
   Task 02: array multidimensi, kondisi status/badge, loop penomoran
   Task 03: grid kartu prioritas dibuat dinamis di sisi server
   ========================================================= */

/* ---------- Task 01: variabel PHP ---------- */
// Padanan variabel di soal: $namaPresiden -> $namaPemilik,
// $statusProgram -> $statusKanvas, $programPrioritas -> $kartuPrioritas
$namaPemilik  = "Tya";
$periode      = "Oktober 2026";
$statusKanvas = "Sedang Dikerjakan";

/* ---------- Task 02: array multidimensi (pengganti db.json) ---------- */
$kartuPrioritas = [
    [
        "ikon"      => "📝",
        "judul"     => "Ide Riset",
        "tipe"      => "Note",
        "deskripsi" => "Bandingkan cara peneliti mencatat ide: dokumen, sticky note, atau papan visual.",
        "jumlah"    => 1,
        "satuan"    => "catatan",
        "status"    => "selesai",
    ],
    [
        "ikon"      => "☑️",
        "judul"     => "Tugas Minggu Ini",
        "tipe"      => "Checklist",
        "deskripsi" => "Kumpulkan referensi, rangkum temuan awal, dan diskusi dengan pembimbing.",
        "jumlah"    => 3,
        "satuan"    => "tugas",
        "status"    => "proses",
    ],
    [
        "ikon"      => "🖼️",
        "judul"     => "Referensi Visual",
        "tipe"      => "Image",
        "deskripsi" => "Moodboard sebagai acuan tampilan kanvas dan susunan kartu.",
        "jumlah"    => 1,
        "satuan"    => "gambar",
        "status"    => "baru",
    ],
    [
        "ikon"      => "📤",
        "judul"     => "Rancangan Ekspor",
        "tipe"      => "Note",
        "deskripsi" => "Catatan rencana ekspor kanvas ke format gambar atau PDF.",
        "jumlah"    => 2,
        "satuan"    => "catatan",
        "status"    => "baru",
    ],
];

// Fungsi kecil agar teks aman ditampilkan di HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// Proses data: tentukan label + kelas badge, beri nomor urut, hitung ringkasan
$total = count($kartuPrioritas);
$jumlahSelesai = 0;
$jumlahProses  = 0;
$jumlahBaru    = 0;
$kartuTampil   = [];

foreach ($kartuPrioritas as $indeks => $kartu) {
    switch ($kartu["status"]) {
        case "selesai":
            $label = "Selesai";
            $kelasBadge = "badge-selesai";
            $jumlahSelesai++;
            break;
        case "proses":
            $label = "Dikerjakan";
            $kelasBadge = "badge-proses";
            $jumlahProses++;
            break;
        default:
            $label = "Belum Mulai";
            $kelasBadge = "badge-baru";
            $jumlahBaru++;
    }

    $kartu["nomor"]      = str_pad($indeks + 1, 2, "0", STR_PAD_LEFT);
    $kartu["labelBadge"] = $label;
    $kartu["kelasBadge"] = $kelasBadge;
    $kartuTampil[]       = $kartu;
}

$persenSelesai = ($total > 0) ? round($jumlahSelesai / $total * 100) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kartu Prioritas — BrainCanvas</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="prioritas.css">
</head>
<body>

  <a class="skip-link" href="#konten">Langsung ke konten utama</a>

  <header>
    <a href="index.html">BrainCanvas</a>
    <nav aria-label="Navigasi utama">
      <ul>
        <li><a href="index.html#beranda">Beranda</a></li>
        <li><a href="index.html#fitur">Fitur</a></li>
        <li><a href="index.html#contoh">Contoh Kanvas</a></li>
        <li><a href="index.html#pengguna">Untuk Siapa</a></li>
        <li><a href="prioritas.php" aria-current="page">Prioritas</a></li>
        <li><a href="app.html">Kanvas</a></li>
        <li><a href="#masuk">Masuk</a></li>
        <li><a href="#daftar">Daftar</a></li>
      </ul>
    </nav>
  </header>

  <main id="konten">
    <section id="prioritas" aria-labelledby="prioritas-title">
      <h2 id="prioritas-title">Kartu Prioritas</h2>

      <!-- Task 01: variabel PHP ditampilkan -->
      <p>
        Papan milik <strong><?= e($namaPemilik) ?></strong> · Periode <?= e($periode) ?> ·
        Status: <span class="badge badge-aktif"><?= e($statusKanvas) ?></span>
      </p>

      <!-- Task 02: hasil pengolahan data -->
      <ul class="ringkasan">
        <li>Total: <?= $total ?> kartu</li>
        <li>Selesai: <?= $jumlahSelesai ?></li>
        <li>Dikerjakan: <?= $jumlahProses ?></li>
        <li>Belum mulai: <?= $jumlahBaru ?></li>
        <li>Progres: <?= $persenSelesai ?>%</li>
      </ul>

      <!-- Task 03: grid kartu dibuat dinamis oleh PHP -->
      <?php if ($total === 0): ?>
        <p>Belum ada kartu prioritas.</p>
      <?php else: ?>
        <div class="grid-kartu">
          <?php foreach ($kartuTampil as $k): ?>
            <article class="kartu-prioritas">
              <div class="atas">
                <span class="nomor"><?= e($k["nomor"]) ?></span>
                <span class="badge <?= e($k["kelasBadge"]) ?>"><?= e($k["labelBadge"]) ?></span>
              </div>
              <span class="ikon" aria-hidden="true"><?= e($k["ikon"]) ?></span>
              <h3><?= e($k["judul"]) ?></h3>
              <p><?= e($k["deskripsi"]) ?></p>
              <p class="meta"><?= e($k["tipe"]) ?> · <?= e($k["jumlah"]) ?> <?= e($k["satuan"]) ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer id="tentang">
    <p>BrainCanvas</p>
    <p>Papan ide visual yang ringan untuk manajemen ide personal.</p>
    <p>&copy; 2026 BrainCanvas. Tugas Web Programming — Pertemuan 5.</p>
  </footer>

</body>
</html>