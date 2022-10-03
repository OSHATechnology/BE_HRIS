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
        Schema::create('instalments', function (Blueprint $table) {
            $table->id('instalmentId');
            $table->unsignedBigInteger('loanId');
            $table->timestamp('date')->nullable();
            $table->integer('nominal')->default('0');
            $table->integer('remainder')->default('0');
            $table->timestamps();

            $table->foreign('loanId')->references('loanId')->on('loans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instalments');
    }
};
