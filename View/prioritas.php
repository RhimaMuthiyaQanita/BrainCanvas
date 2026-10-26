<?php
require __DIR__ . "/partials/header.php";
?>
  <main id="konten">
    <section id="prioritas" aria-labelledby="prioritas-title">
      <h2 id="prioritas-title">Kartu Prioritas</h2>

      <p>
        <?= e($profil) ?> ·
        Status: <span class="badge badge-aktif"><?= e($status) ?></span>
      </p>

      <ul class="ringkasan">
        <li>Total: <?= e($ringkasan["total"]) ?> kartu</li>
        <li>Selesai: <?= e($ringkasan["selesai"]) ?></li>
        <li>Dikerjakan: <?= e($ringkasan["proses"]) ?></li>
        <li>Belum mulai: <?= e($ringkasan["baru"]) ?></li>
        <li>Progres: <?= e($ringkasan["persen"]) ?>%</li>
      </ul>

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
              <a class="detail-link" href="prioritas.php?aksi=show&amp;id=<?= (int) $k["nomor"] ?>">Lihat detail →</a>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
<?php require __DIR__ . "/partials/footer.php"; ?>