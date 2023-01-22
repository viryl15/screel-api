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
        Schema::create('user_follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_id')->nullable();
            $table->foreign('follower_id')->references('id')->on('users')
                ->onDelete('SET NULL');
            $table->unsignedBigInteger('following_id')->nullable();
            $table->foreign('following_id')->references('id')->on('users')
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
        Schema::dropIfExists('user_follows');
    }
};
