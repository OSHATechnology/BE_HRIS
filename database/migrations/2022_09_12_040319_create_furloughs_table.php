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
        Schema::create('furloughs', function (Blueprint $table) {
            $table->id('furloughId');
            $table->unsignedBigInteger('furTypeId');
            $table->unsignedBigInteger('employeeId');
            $table->timestamp('startAt');
            $table->timestamp('endAt')->nullable();
            $table->boolean('isConfirmedBy')->default(0);
            $table->unsignedBigInteger('confirmedBy');
            $table->timestamp('lastFurloughAt')->nullable();
            $table->timestamps();

            $table->foreign('employeeId')->references('employeeId')->on('employees');
            $table->foreign('furTypeId')->references('furTypeId')->on('furlough_types');
            $table->foreign('confirmedBy')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('furloughs');
    }
};
