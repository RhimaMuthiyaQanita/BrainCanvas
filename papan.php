<?php
/* =========================================================
   BrainCanvas — Papan.php (Week 6, Sprint 05)
   Class Papan mewakili data papan ide: pemilik, periode,
   status, dan daftar kartu prioritas.
   Padanan di soal: class President (name, period, programs).
   ========================================================= */

class Papan
{
    /* ---------- Property (private = encapsulation) ---------- */
    private string $namaPemilik;
    private string $periode;
    private string $statusKanvas;
    private array  $kartuPrioritas;

    // Status kartu -> [label tampilan, kelas CSS badge]
    private const STATUS_KARTU = [
        "selesai" => ["Selesai",    "badge-selesai"],
        "proses"  => ["Dikerjakan", "badge-proses"],
    ];
    private const STATUS_BAWAAN = ["Belum Mulai", "badge-baru"];

    /* ---------- Constructor: mengisi data awal object ---------- */
    public function __construct(string $namaPemilik, string $periode, string $statusKanvas, array $kartuPrioritas)
    {
        $this->namaPemilik    = $namaPemilik;
        $this->periode        = $periode;
        $this->kartuPrioritas = $kartuPrioritas;
        $this->setStatusKanvas($statusKanvas);   // lewat setter agar divalidasi
    }

    /* ---------- Getter & setter ---------- */
    public function getNamaPemilik(): string
    {
        return $this->namaPemilik;
    }

    public function getPeriode(): string
    {
        return $this->periode;
    }

    public function getStatusKanvas(): string
    {
        return $this->statusKanvas;
    }

    public function setStatusKanvas(string $status): void
    {
        $status = trim($status);
        $this->statusKanvas = ($status !== "") ? $status : "Belum Diatur";
    }

    /* ---------- Method: perilaku object ---------- */

    // Teks profil singkat untuk ditampilkan di halaman
    public function getProfil(): string
    {
        return "Papan milik " . $this->namaPemilik . " · Periode " . $this->periode;
    }

    // Daftar kartu + nomor urut + label dan kelas badge status
    public function getKartu(): array
    {
        $hasil = [];

        foreach ($this->kartuPrioritas as $indeks => $kartu) {
            [$label, $kelas] = self::STATUS_KARTU[$kartu["status"]] ?? self::STATUS_BAWAAN;

            $kartu["nomor"]      = str_pad($indeks + 1, 2, "0", STR_PAD_LEFT);
            $kartu["labelBadge"] = $label;
            $kartu["kelasBadge"] = $kelas;
            $hasil[] = $kartu;
        }

        return $hasil;
    }

    // Ringkasan jumlah kartu per status dan persentase progres
    public function getRingkasan(): array
    {
        $total   = count($this->kartuPrioritas);
        $selesai = $this->hitungStatus("selesai");
        $proses  = $this->hitungStatus("proses");

        return [
            "total"   => $total,
            "selesai" => $selesai,
            "proses"  => $proses,
            "baru"    => $total - $selesai - $proses,
            "persen"  => ($total > 0) ? round($selesai / $total * 100) : 0,
        ];
    }

    // Method private: hanya dipakai di dalam class
    private function hitungStatus(string $status): int
    {
        $jumlah = 0;
        foreach ($this->kartuPrioritas as $kartu) {
            if ($kartu["status"] === $status) {
                $jumlah++;
            }
        }
        return $jumlah;
    }
}