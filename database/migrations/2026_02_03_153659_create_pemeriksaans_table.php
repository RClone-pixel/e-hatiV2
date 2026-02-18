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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal_pemeriksaan');
            $table->boolean('puasa')->default(false);

            // BMI
            $table->decimal('tinggi_badan', 6, 1)->nullable();
            $table->decimal('berat_badan', 6, 1)->nullable();

            // Blood Pressure
            $table->integer('sistolik')->nullable();
            $table->integer('diastolik')->nullable();
            $table->integer('nadi')->nullable();

            // Blood Sugar
            $table->decimal('nilai_glukometer', 6, 1)->nullable();
            $table->string('parameter_gula', 10)->nullable(); // GDS, GDP, GD2PP

            // Cholesterol
            $table->decimal('kolesterol_total', 6, 1)->nullable();

            // Uric Acid
            $table->decimal('asam_urat', 5, 1)->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('pegawai_id')
            ->references('id')
            ->on('pegawais')
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
