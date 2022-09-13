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
        Schema::create('teams', function (Blueprint $table) {
            $table->id('teamId');
            $table->string('name');
            $table->unsignedBigInteger('leadBy');
            $table->unsignedBigInteger('createdBy');
            $table->timestamps();

            $table->foreign('leadBy')->references('employeeId')->on('employees');
            $table->foreign('createdBy')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
