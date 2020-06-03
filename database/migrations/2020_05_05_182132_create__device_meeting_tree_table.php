<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceMeetingTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceMeetingTree', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('UId');
            $table->timestamp('PeriodStart')->nullable();
            $table->timestamp('PeriodEnd')->nullable();
            $table->unsignedTinyInteger('CoefficientP')->nullable()->default(null);
            $table->unsignedInteger('TotalMeets');
            $table->unsignedInteger('MeetsByGEO');
            $table->unsignedInteger('MeetsByMeets');
            $table->unsignedInteger('MeetsByCheckPoints');
            $table->boolean('IsBuilded')->default(false);
            $table->integer('CalcTime');
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
        Schema::table('DeviceMeetingTree', function (Blueprint $table) {
            //
        });
    }
}
