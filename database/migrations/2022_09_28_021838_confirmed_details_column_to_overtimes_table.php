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
        Schema::table('overtimes', function (Blueprint $table) {
            $table->boolean('isConfirmed')->default(0)->after('assignedBy');
            $table->unsignedBigInteger('confirmedBy')->nullable()->after('isConfirmed');

            $table->foreign('confirmedBy')->references('employeeId')->on('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            //
        });
    }
};
