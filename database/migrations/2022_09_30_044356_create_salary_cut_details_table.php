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
            $table->unsignedBigInteger('salaryId');
            $table->integer('totalAttendance')->default('0');
            $table->integer('attdFeeReduction')->default('0');
            $table->unsignedBigInteger('loanId');
            $table->integer('etc')->default('0');
            $table->integer('total')->default('0');
            $table->integer('net')->default('0');
            $table->timestamps();

            $table->foreign('salaryId')->references('salaryId')->on('salaries');
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
        Schema::dropIfExists('salary_cut_details');
    }
};
