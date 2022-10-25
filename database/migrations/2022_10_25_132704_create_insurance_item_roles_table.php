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
        Schema::create('insurance_item_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insItemId');
            $table->unsignedBigInteger('roleId');
            $table->timestamps();

            $table->foreign('insItemId')->references('insItemId')->on('insurance_items')->cascadeOnDelete();
            $table->foreign('roleId')->references('roleId')->on('roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_item_roles');
    }
};
