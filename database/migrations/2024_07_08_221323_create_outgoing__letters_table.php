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
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('reference_letter_id');
            $table->string('nomer_surat_keluar');
            $table->string('nomer_surat_keluark_idx');
            $table->date('tanggal_surat_keluar');
            $table->string('nama_penerima');
            $table->string('email_penerima');
            $table->string('keterangan');
            $table->string('file')->nullable();
            $table->unsignedBigInteger('category_surat_id');
            $table->foreign('category_surat_id')->references('id')->on('category_outgoing_letters')->onDelete('cascade');
            $table->enum('status', ['Sent', 'Archived'])->default('Sent');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable()->index();

            $table->foreign('reference_letter_id')->references('id')->on('incoming_letters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing__letters');
    }
};
