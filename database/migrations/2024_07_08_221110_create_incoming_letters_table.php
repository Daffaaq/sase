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
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_surat_masuk');
            $table->string('nomer_surat_masuk_idx');
            $table->date('tanggal_surat_masuk');
            $table->string('nama_pengirim');
            $table->string('email_pengirim');
            $table->string('keterangan');
            $table->string('file')->nullable();
            $table->unsignedBigInteger('category_surat_id');
            $table->foreign('category_surat_id')->references('id')->on('category_incoming_letters')->onDelete('cascade');
            $table->unsignedBigInteger('sifat_surat_id');
            $table->foreign('sifat_surat_id')->references('id')->on('sifat_incoming_letters')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->enum('disposition_status', ['Pending', 'Disposition Sent'])->default('Pending');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_letters');
    }
};
