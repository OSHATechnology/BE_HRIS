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
        Schema::create('basic_salary_by_employees', function (Blueprint $table) {
            $table->id('basicSalaryByEmployeeId');
            $table->unsignedBigInteger('empId');
            $table->unsignedBigInteger('basicSalaryByRoleId');
            $table->integer('fee');
            $table->timestamps();

            $table->foreign('empId')->references('employeeId')->on('employees');
            $table->foreign('basicSalaryByRoleId')->references('basicSalaryByRoleId')->on('basic_salary_by_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basic_salary_by_employees');
    }
};
