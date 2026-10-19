<?php
/* =========================================================
   BrainCanvas — prioritas.php (Week 6, Sprint 05)
   PHP OOP: data dikelola object dari class Papan (Papan.php).
   Task 01: class Papan dibuat (property, constructor)
   Task 02: property dan method (getProfil, getKartu, getRingkasan)
   Task 03: object dipakai untuk menghasilkan HTML dinamis
   ========================================================= */

require __DIR__ . "/Papan.php";

/* ---------- Sumber data (array dari Week 5) ---------- */
$namaPemilik  = "Tya";
$periode      = "Oktober 2026";
$statusKanvas = "Sedang Dikerjakan";

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

/* ---------- Task 01: buat object dari class Papan ---------- */
$papan = new Papan($namaPemilik, $periode, $statusKanvas, $kartuPrioritas);

/* ---------- Task 02: data diolah lewat method object ---------- */
$kartuTampil = $papan->getKartu();
$ringkasan   = $papan->getRingkasan();
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

      <!-- Data profil dari method object -->
      <p>
        <?= e($papan->getProfil()) ?> ·
        Status: <span class="badge badge-aktif"><?= e($papan->getStatusKanvas()) ?></span>
      </p>

      <!-- Ringkasan dari method getRingkasan() -->
      <ul class="ringkasan">
        <li>Total: <?= $ringkasan["total"] ?> kartu</li>
        <li>Selesai: <?= $ringkasan["selesai"] ?></li>
        <li>Dikerjakan: <?= $ringkasan["proses"] ?></li>
        <li>Belum mulai: <?= $ringkasan["baru"] ?></li>
        <li>Progres: <?= $ringkasan["persen"] ?>%</li>
      </ul>

      <!-- Task 03: grid kartu dibuat dari data object -->
      <?php if ($ringkasan["total"] === 0): ?>
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
    <p>&copy; 2026 BrainCanvas. Tugas Web Programming — Pertemuan 6.</p>
  </footer>

</body>
</html>