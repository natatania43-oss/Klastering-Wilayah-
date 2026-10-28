<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('cluster_no')->unique();
            $table->string('label');
            $table->unsignedInteger('jumlah_tps')->default(0);
            $table->decimal('rata_partisipasi', 8, 4)->default(0);
            $table->decimal('std_partisipasi', 8, 4)->default(0);
            $table->decimal('min_partisipasi', 8, 4)->default(0);
            $table->decimal('max_partisipasi', 8, 4)->default(0);
            $table->decimal('rata_dpt', 8, 2)->default(0);
            $table->decimal('rata_disabilitas', 8, 2)->default(0);
            $table->decimal('persen_thd_total', 8, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_results');
    }
};