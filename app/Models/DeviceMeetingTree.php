<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceMeetingTree extends Model
{
    protected $table = 'DeviceMeetingTree';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'UId',
        'PeriodStart',
        'PeriodEnd',
        'CoefficientP',
        'TotalMeets',
        'MeetsByGEO',
        'MeetsByMeets',
        'MeetsByCheckPoints',
        'IsBuilded',
        'CalcTime'];

    const UPDATED_AT = 'EditDateTime';

    public function elements()
    {
        return $this->hasMany(DeviceMeetingTreeElement::class, 'TreeId', 'Id');
    }
}
