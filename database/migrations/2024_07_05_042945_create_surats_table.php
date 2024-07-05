<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->string('no_surat_idx');
            $table->string('file');
            $table->string('nama_file');
            $table->string('nama_pengirim')->nullable();
            $table->string('email_pengirim')->nullable();
            $table->string('instansi_pengirim')->nullable();
            $table->string('no_telp_pengirim')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Diarsipkan']);
            $table->enum('status_letter', ['surat_in', 'surat_out', 'surat_internal', 'surat_pegawai']);
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id');
            $table->foreignId('forwarded_to')->nullable()->constrained('users', 'id');
            $table->string('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
