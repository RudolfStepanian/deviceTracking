<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceGeoTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceGeoTrack', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('UId');
            $table->decimal('Lat', 12, 8);
            $table->decimal('Lon', 12, 8);
            $table->decimal('Alt', 12, 8);
            $table->timestamp('DateTimeStart')->nullable();
            $table->unsignedInteger('StayTime');
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
        Schema::table('DeviceGeoTrackTable', function (Blueprint $table) {
            //
        });
    }
}
