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
        Schema::create('partners', function (Blueprint $table) {
            $table->id('partnerId');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('resposibleBy');
            $table->string('phone');
            $table->longText('address');
            $table->longText('photo');
            $table->unsignedBigInteger('assignedBy');
            $table->timestamp('joinedAt')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('partners');
    }
};
