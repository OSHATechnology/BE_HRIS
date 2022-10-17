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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id('overtimeId');
            $table->unsignedBigInteger('employeeId');
            $table->timestamp('startAt')->nullable();
            $table->timestamp('endAt')->nullable();
            $table->unsignedBigInteger('assignedBy');
            $table->timestamps();

            $table->foreign('assignedBy')->references('employeeId')->on('employees');
            $table->foreign('employeeId')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
};
