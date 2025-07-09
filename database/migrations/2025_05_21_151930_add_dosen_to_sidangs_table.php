<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sidangs', function (Blueprint $table) {
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('dosen_penguji1_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('dosen_penguji2_id')->nullable()->constrained('dosens')->onDelete('set null');
            // Jika ada ketua sidang di tabel sidang
            // $table->foreignId('ketua_sidang_dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('sidangs', function (Blueprint $table) {
            $table->dropForeign(['dosen_pembimbing_id']);
            $table->dropForeign(['dosen_penguji1_id']);
            $table->dropForeign(['dosen_penguji2_id']);
            // $table->dropForeign(['ketua_sidang_dosen_id']);

            $table->dropColumn(['dosen_pembimbing_id', 'dosen_penguji1_id', 'dosen_penguji2_id']);
            // $table->dropColumn(['ketua_sidang_dosen_id']);
        });
    }
};
