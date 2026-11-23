<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Membuat tabel cards (php artisan migrate)
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                       // nama kartu
            $table->text('deskripsi')->nullable();        // isi / keterangan kartu
            $table->string('tipe')->default('note');      // note, todo, image
            $table->string('status')->default('baru');    // baru, proses, selesai
            $table->integer('x')->default(40);            // posisi di kanvas
            $table->integer('y')->default(40);
            $table->timestamps();                         // created_at, updated_at
        });
    }

    // Membatalkan migration (php artisan migrate:rollback)
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
