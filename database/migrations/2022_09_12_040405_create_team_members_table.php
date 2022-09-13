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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id('memberId');
            $table->unsignedBigInteger('teamId');
            $table->unsignedBigInteger('empId');
            $table->unsignedBigInteger('assignedBy');
            $table->timestamp('joinedAt')->nullable();
            $table->timestamps();

            $table->foreign('teamId')->references('teamId')->on('teams');
            $table->foreign('empId')->references('employeeId')->on('employees');
            $table->foreign('assignedBy')->references('employeeId')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_members');
    }
};
