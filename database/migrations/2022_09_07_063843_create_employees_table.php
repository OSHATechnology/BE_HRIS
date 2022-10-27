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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employeeId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('photo');
            $table->enum('gender', ['man', 'woman']);
            $table->date('birthDate');
            $table->longText('address');
            $table->string('city');
            $table->string('nation');
            $table->unsignedBigInteger('roleId')->nullable();
            $table->boolean('isActive');
            $table->timestamp('emailVerifiedAt')->nullable();
            $table->rememberToken();
            $table->timestamp('joinedAt')->nullable();
            $table->timestamp('resignedAt')->nullable();
            $table->unsignedBigInteger('statusHireId');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('roleId')->references('roleId')->on('roles')->nullOnDelete();
            $table->foreign('statusHireId')->references('statusHireId')->on('status_hires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
