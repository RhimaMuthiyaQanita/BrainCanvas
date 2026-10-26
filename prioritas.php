<?php
/* =========================================================
   BrainCanvas — prioritas.php (Week 7, MVC)
   Titik masuk aplikasi: memuat Model dan Controller, lalu
   mengarahkan request ke method Controller yang sesuai.
   ========================================================= */

require __DIR__ . "/Model/Papan.php";
require __DIR__ . "/Controller/PapanController.php";

$controller = new PapanController();
$aksi = $_GET["aksi"] ?? "index";

if ($aksi === "show") {
    $controller->show((int) ($_GET["id"] ?? 0));
} else {
    $controller->index();
}