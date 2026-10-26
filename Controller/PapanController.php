<?php
/* =========================================================
   BrainCanvas — Controller/PapanController.php (Week 7, MVC)
   CONTROLLER: menerima request, meminta data ke Model,
   lalu meneruskannya ke View.
   Alur: Request -> Controller -> Model -> Controller -> View
   ========================================================= */

class PapanController
{
    private Papan $papan;

    public function __construct()
    {
        $this->papan = Papan::ambil();          // Controller memanggil Model
    }

    // Request: prioritas.php  -> daftar kartu prioritas
    public function index(): void
    {
        $this->render("prioritas", $this->process());
    }

    // Request: prioritas.php?aksi=show&id=2  -> detail satu kartu
    public function show(int $nomor): void
    {
        $detail = $this->papan->cariKartu($nomor);

        if ($detail === null) {
            http_response_code(404);
            $this->render("tidak-ditemukan", ["judulHalaman" => "Kartu Tidak Ditemukan"]);
            return;
        }

        $this->render("kartu-detail", [
            "judulHalaman" => $detail["judul"],
            "detail"       => $detail,
        ]);
    }

    // Proses data dari Model menjadi bahan untuk View
    private function process(): array
    {
        return [
            "judulHalaman" => "Kartu Prioritas",
            "profil"       => $this->papan->getProfil(),
            "status"       => $this->papan->getStatusKanvas(),
            "ringkasan"    => $this->papan->getRingkasan(),
            "kartuTampil"  => $this->papan->getKartu(),
        ];
    }

    // Kirim data ke View (file di folder View/)
    private function render(string $view, array $data): void
    {
        extract($data);
        require_once __DIR__ . "/../View/helpers.php";
        require __DIR__ . "/../View/" . $view . ".php";
    }
}