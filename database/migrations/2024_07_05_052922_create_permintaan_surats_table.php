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
        Schema::create('permintaan_surats', function (Blueprint $table) {
            $table->id();
            $table->string('request_title');
            $table->text('request_content');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->foreignId('requested_by')->nullable()->constrained('users', 'id');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id');
            $table->foreignId('generated_letter_id')->nullable()->constrained('surats', 'id');
            $table->string('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_surats');
    }
};
