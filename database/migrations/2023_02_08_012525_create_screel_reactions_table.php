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
        Schema::create('screel_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reaction_id')->nullable();
            $table->foreign('reaction_id')->references('id')->on('reactions')
                ->onDelete('SET NULL');
            $table->unsignedInteger("count")->default(0);
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
        Schema::dropIfExists('screel_reactions');
    }
};
