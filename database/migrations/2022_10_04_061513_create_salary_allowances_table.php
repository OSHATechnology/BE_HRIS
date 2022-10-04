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
        Schema::create('salary_allowances', function (Blueprint $table) {
            $table->id("salaryAllowanceId");
            $table->unsignedBigInteger("salaryId");
            $table->string("allowanceName");
            $table->integer("nominal");
            $table->timestamps();

            $table->foreign("salaryId")->references("salaryId")->on("salaries");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_allowances');
    }
};
