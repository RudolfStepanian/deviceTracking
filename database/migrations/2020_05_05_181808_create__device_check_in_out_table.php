<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceCheckInOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceCheckInOut', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('UId');
            $table->unsignedBigInteger('CheckPointId');
            $table->unsignedTinyInteger('CheckPointType');
            $table->decimal('Lat', 12, 8);
            $table->decimal('Lon', 12, 8);
            $table->timestamp('DateTimeCheck')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('DeviceCheckInOut', function (Blueprint $table) {
            //
        });
    }
}
