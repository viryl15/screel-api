<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screel_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('screel_id')->nullable();
            $table->foreign('screel_id')->references('id')->on('screels')
                ->onDelete('SET NULL');
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags')
                ->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('screel_tags');
    }
};
