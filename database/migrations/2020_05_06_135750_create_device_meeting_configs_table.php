<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceMeetingConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceMeetingConfig', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('CountOfFork')->default(10);
            $table->tinyInteger('TreeLevel')->default(1);
            $table->tinyInteger('MinimumMeetTime')->default(0);
            $table->tinyInteger('MaximumGeoRange')->default(20);
            $table->tinyInteger('AltitudeRange')->default(3);
            $table->tinyInteger('IsActive');
            $table->timestamp('StartDateTime')->useCurrent();
            $table->timestamp('EndDateTime')->nullable();
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
        Schema::dropIfExists('device_meeting_configs');
    }
}
