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
        Schema::create('work_permits', function (Blueprint $table) {
            $table->id('workPermitId');
            $table->unsignedBigInteger('employeeId');
            $table->timestamp('startAt');
            $table->timestamp('endAt')->nullable();
            $table->boolean('isConfirmed')->default(0);
            $table->unsignedBigInteger('confirmedBy')->nullable();
            $table->timestamp('confirmedAt')->nullable();
            $table->timestamps();

            $table->foreign('confirmedBy')->references('employeeId')->on('employees')->nullOnDelete();
            $table->foreign('employeeId')->references('employeeId')->on('employees')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_permits');
    }
};
