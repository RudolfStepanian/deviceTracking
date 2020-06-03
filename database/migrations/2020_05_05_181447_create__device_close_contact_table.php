<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceCloseContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceCloseContact', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('UId1');
            $table->unsignedBigInteger('UId2');
            $table->decimal('Lat', 12, 8);
            $table->decimal('Lon', 12, 8);
            $table->timestamp('DateTimeStart')->nullable();
            $table->unsignedInteger('StayTime');
            $table->unsignedTinyInteger('Distance');
            $table->timestamp('EditDateTime')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('DeviceCloseContact', function (Blueprint $table) {
            //
        });
    }
}
