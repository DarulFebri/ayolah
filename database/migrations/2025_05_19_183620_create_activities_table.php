<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Siapa yang melakukan aktivitas (jika terkait user)
            $table->string('activity'); // Deskripsi aktivitas
            $table->string('module')->nullable(); // Modul yang terkait dengan aktivitas
            $table->string('ip_address')->nullable(); // Alamat IP user
            $table->text('user_agent')->nullable(); // Informasi browser user
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
