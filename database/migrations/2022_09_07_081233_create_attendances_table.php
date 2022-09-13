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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendId');
            $table->unsignedBigInteger('employeeId');
            $table->unsignedBigInteger('attendanceStatusId');
            $table->timestamp('submitedAt')->nullable();
            $table->unsignedBigInteger('submitedById');
            $table->string('typeInOut');
            $table->timestamp('timeAttend')->nullable();
            $table->timestamps();

            $table->foreign('employeeId')->references('employeeId')->on('employees');
            $table->foreign('attendanceStatusId')->references('attendanceStatusId')->on('attendance_statuses');
            $table->foreign('submitedById')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
