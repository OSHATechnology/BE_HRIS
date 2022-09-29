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
        Schema::create('salary_insurance_details', function (Blueprint $table) {
            $table->id('salaryInsId');
            $table->unsignedBigInteger('salaryId');
            $table->unsignedBigInteger('insItemId');
            $table->integer('nominal')->default(0);
            $table->timestamp('date')->nullable();
            $table->timestamps();

            $table->foreign('salaryId')->references('salaryId')->on('salaries');
            $table->foreign('insItemId')->references('insItemId')->on('insurance_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_insurance_details');
    }
};
