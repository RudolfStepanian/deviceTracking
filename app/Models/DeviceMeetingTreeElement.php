<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceMeetingTreeElement extends Model
{
    protected $table = 'DeviceMeetingTreeElement';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'TreeId',
        'UId',
        'PeriodStart',
        'PeriodEnd',
        'CoefficientP',
        'TotalMeets',
        'Lat',
        'Lon',
        'MeetType',
        'CalcTime'];

    const CREATED_AT = null;
    const UPDATED_AT = 'EditDateTime';

    public function tree()
    {
        return $this->belongsTo(DeviceMeetingTree::class, 'TreeId', 'Id');
    }
}
