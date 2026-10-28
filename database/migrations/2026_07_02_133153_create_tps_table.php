<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->string('id_tps')->unique();
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('dapil')->nullable();
            $table->unsignedInteger('no_tps')->nullable();
            $table->unsignedInteger('dpt_lk')->default(0);
            $table->unsignedInteger('dpt_pr')->default(0);
            $table->unsignedInteger('dpt_jml')->default(0);
            $table->unsignedInteger('pdpt_lk')->default(0);
            $table->unsignedInteger('pdpt_pr')->default(0);
            $table->unsignedInteger('pdpt_jml')->default(0);
            $table->unsignedInteger('dptb')->default(0);
            $table->unsignedInteger('dpk')->default(0);
            $table->unsignedInteger('total_pengguna')->default(0);
            $table->decimal('partisipasi', 8, 4)->default(0);
            $table->unsignedInteger('ss_diterima')->default(0);
            $table->unsignedInteger('ss_digunakan')->default(0);
            $table->unsignedInteger('disabilitas')->default(0);
            // Hasil clustering (bisa dihitung ulang lewat: php artisan kmeans:run)
            $table->unsignedTinyInteger('cluster')->nullable();
            $table->timestamps();

            $table->index(['kecamatan', 'kelurahan']);
            $table->index('cluster');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};