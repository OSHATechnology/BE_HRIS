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
        Schema::create('loans', function (Blueprint $table) {
            $table->id('loanId');
            $table->unsignedBigInteger('empId');
            $table->string('name');
            $table->integer('nominal')->default(0);
            $table->timestamp('loanDate')->nullable();
            $table->timestamp('paymentDate')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('empId')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
