<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceMeetingConfig extends Model
{
    protected $table = 'DeviceMeetingConfig';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'CountOfFork',
        'TreeLevel',
        'MinimumMeetTime',
        'MaximumGeoRange',
        'AltitudeRange',
        'IsActive',
        'StartDateTime',
        'EndDateTime'];

    const UPDATED_AT = 'EditDateTime';
}
