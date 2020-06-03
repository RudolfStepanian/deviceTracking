<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceMeetingTreeElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceMeetingTreeElement', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('TreeId');
            $table->unsignedBigInteger('UId');
            $table->tinyInteger('MeetType');
            $table->timestamp('PeriodStart')->nullable();
            $table->timestamp('PeriodEnd')->nullable();
            $table->decimal('Lat', 12, 8);
            $table->decimal('Lon', 12, 8);
            $table->timestamp('EditDateTime')->useCurrent();


            $table->foreign('TreeId')->references('id')->on('DeviceMeetingTree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('DeviceMeetingTreeElement', function (Blueprint $table) {
            //
        });
    }
}
