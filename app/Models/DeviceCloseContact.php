<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCloseContact extends Model
{
    protected $table = 'DeviceCloseContact';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'UId1',
        'UId2',
        'Lat',
        'Lon',
        'DateTimeStart',
        'StayTime',
        'Distance'];

    const UPDATED_AT = 'EditDateTime';
}
