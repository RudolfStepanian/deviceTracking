<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCheckInOut extends Model
{
    protected $table = 'DeviceCheckInOut';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'UId',
        'CheckPointId',
        'CheckPointType',
        'Lat',
        'Lon',
        'DateTimeCheck'];
}
