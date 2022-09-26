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
        Schema::create('salary_cut_details', function (Blueprint $table) {
            $table->id('salaryCutDetailsId');
            $table->integer('totalAttendance')->default('0');
            $table->integer('attdFeeReduction')->default('0');
            $table->integer('bpjs')->default('0');
            $table->integer('loan')->default('0');
            $table->integer('etc')->default('0');
            $table->integer('total')->default('0');
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
        Schema::dropIfExists('salary_cut_details');
    }
};
