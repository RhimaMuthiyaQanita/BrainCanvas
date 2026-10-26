<?php
/* =========================================================
   BrainCanvas — Model/Papan.php (Week 7, MVC)
   MODEL: mengelola data papan (pemilik, periode, status, kartu)
   dan akses datanya. Tidak berisi HTML dan tidak tahu soal View.
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

    // Data access: sumber data kartu (pengganti db.json / array di halaman)
    public static function ambil(): self
    {
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

        return new self("Tya", "Oktober 2026", "Sedang Dikerjakan", $kartuPrioritas);
    }

    // Cari satu kartu berdasarkan nomor urut (1, 2, 3, ...); null jika tidak ada
    public function cariKartu(int $nomor): ?array
    {
        foreach ($this->getKartu() as $kartu) {
            if ((int) $kartu["nomor"] === $nomor) {
                return $kartu;
            }
        }
        return null;
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