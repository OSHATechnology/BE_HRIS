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
        Schema::create('insurance_items', function (Blueprint $table) {
            $table->id('insItemId');
            $table->unsignedBigInteger('insuranceId');
            $table->string('name');
            $table->enum('type',['allowance','deduction']);
            $table->float('percent');
            $table->timestamps();

            $table->foreign('insuranceId')->references('insuranceId')->on('insurances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_items');
    }
};
