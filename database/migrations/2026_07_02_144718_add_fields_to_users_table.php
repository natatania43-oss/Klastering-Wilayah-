<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom role dengan panjang yang cukup
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('admin'); // Perhatikan panjang 50
            }
            
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan', 100)->nullable();
            }
            
            if (!Schema::hasColumn('users', 'no_telepon')) {
                $table->string('no_telepon', 20)->nullable();
            }
            
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto', 255)->nullable();
            }
            
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['role', 'jabatan', 'no_telepon', 'foto', 'is_active'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};