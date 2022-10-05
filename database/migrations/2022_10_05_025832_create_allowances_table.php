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
        Schema::create('allowances', function (Blueprint $table) {
            $table->id("allowanceId");
            $table->unsignedBigInteger("roleId");
            $table->unsignedBigInteger("typeId");
            $table->timestamps();

            $table->foreign("roleId")->references("roleId")->on('roles');
            $table->foreign("typeId")->references("typeId")->on('type_of_allowances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowances');
    }
};
