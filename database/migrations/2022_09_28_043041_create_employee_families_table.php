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
        Schema::create('employee_families', function (Blueprint $table) {
            $table->id('idEmpFam');
            $table->unsignedBigInteger('empId');
            $table->string('identityNumber')->unique();
            $table->string('name');
            $table->unsignedBigInteger('statusId');
            $table->boolean('isAlive')->default(1);
            $table->timestamps();

            $table->foreign('statusId')->references('empFamStatId')->on('employee_family_statuses');
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
        Schema::dropIfExists('employee_families');
    }
};
