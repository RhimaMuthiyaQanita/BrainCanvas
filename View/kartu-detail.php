<?php
/* VIEW: detail satu kartu. Variabel: $judulHalaman, $detail */
require __DIR__ . "/partials/header.php";
?>
  <main id="konten">
    <section id="detail" aria-labelledby="detail-title">
      <h2 id="detail-title">Detail Kartu <?= e($detail["nomor"]) ?></h2>

      <article class="kartu-prioritas kartu-detail">
        <div class="atas">
          <span class="nomor"><?= e($detail["nomor"]) ?></span>
          <span class="badge <?= e($detail["kelasBadge"]) ?>"><?= e($detail["labelBadge"]) ?></span>
        </div>
        <span class="ikon" aria-hidden="true"><?= e($detail["ikon"]) ?></span>
        <h3><?= e($detail["judul"]) ?></h3>
        <p><?= e($detail["deskripsi"]) ?></p>
        <p class="meta"><?= e($detail["tipe"]) ?> · <?= e($detail["jumlah"]) ?> <?= e($detail["satuan"]) ?></p>
      </article>

      <p><a class="detail-link" href="prioritas.php">← Kembali ke daftar</a></p>
    </section>
  </main>
<?php require __DIR__ . "/partials/footer.php"; ?>