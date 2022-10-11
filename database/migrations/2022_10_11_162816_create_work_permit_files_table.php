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
        Schema::create('work_permit_files', function (Blueprint $table) {
            $table->id('fileId');
            $table->unsignedBigInteger('workPermitId')->nullable();
            $table->string('name')->nullable();
            $table->string('path');
            $table->timestamps();

            $table->foreign('workPermitId')->references('workPermitId')->on('work_permits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_permit_files');
    }
};
