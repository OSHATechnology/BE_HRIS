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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notifId');
            $table->unsignedBigInteger('empId');
            $table->string('name');
            $table->string('content');
            $table->string('type');
            $table->unsignedBigInteger('senderBy');
            $table->timestamp('scheduleAt');
            $table->string('status');
            $table->timestamps();

            $table->foreign('empId')->references('employeeId')->on('employees');
            $table->foreign('senderBy')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
