<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceGeoTrack extends Model
{
    protected $table = 'device_geo_track_table';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'UId',
        'Lat',
        'Lon',
        'Alt',
        'DateTimeStart',
        'StayTime'];

    const UPDATED_AT = 'EditDateTime';
}
