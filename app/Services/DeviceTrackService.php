<?php
namespace App\Services;

use App\Model\DeviceCloseContact;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\DeviceCheckInOut;
use App\Models\DeviceMeetingTree;
use App\Models\DeviceMeetingTreeElement;
use App\Models\DeviceMeetingConfig;
use Log;
use DB;

class DeviceTrackService
{
    public function findMeetsByGEO($configuration, $tree)
    {
        $count = 0;
        $dbData = DB::select('
            SELECT t1.UId AS T1UID, t2.UId as T2UID,
            6371000 * 2 * ASIN(SQRT(POWER(SIN((t2.Lat - abs(t1.Lat)) * pi()/180 / 2), 2)
                + COS(t2.Lat * pi()/180 ) * COS(abs(t1.Lat) * pi()/180)* POWER(SIN((t2.Lon - t1.Lon) * pi()/180 / 2), 2) )) as distance,
            t1.Lat AS T1Lat, t1.Lon AS T1Lon, t1.Alt AS T1Alt, t2.Lat AS T2Lat, t2.Alt AS T2Alt, t2.Lon AS T2Lon,
            t1.DateTimeStart AS T1Start, t1.StayTime AS T1Stay, t1.DateTimeStart + INTERVAL t1.StayTime MINUTE AS T1Finish,
            t2.DateTimeStart AS T2Start, t2.StayTime AS T2Stay, t2.DateTimeStart + INTERVAL t2.StayTime MINUTE AS T2Finish
            FROM `DeviceGeoTrack` AS t1 JOIN `DeviceGeoTrack` AS t2
            ON t1.UId != t2.UId
            WHERE (t1.DateTimeStart <= "'. $configuration->EndDateTime .'" OR t1.DateTimeStart + INTERVAL t1.StayTime MINUTE >= "'. $configuration->StartDateTime .'")
            AND (6371000 * 2 * ASIN(SQRT(POWER(SIN((t2.Lat - abs(t1.Lat)) * pi()/180 / 2), 2) + COS(t2.Lat * pi()/180 ) * COS(abs(t1.Lat) * pi()/180)
                * POWER(SIN((t2.Lon - t1.Lon) * pi()/180 / 2), 2) ))) <= '. $configuration->MaximumGeoRange .'
            AND ABS(t1.Alt - t2.Alt) <= '. $configuration->AltitudeRange .'
        ');

        $dbResult = [];
        $result = [];
        foreach ($dbData as $el){
            $s1 = new \DateTime($el->T1Start);
            $s2 = new \DateTime($el->T2Start);
            $f1 = $s1->add(new \DateInterval('PT' . $el->T1Stay . 'M'));
            $f2 = $s2->add(new \DateInterval('PT' . $el->T2Stay . 'M'));
            $s1 = new \DateTime($el->T1Start);
            $s2 = new \DateTime($el->T2Start);

            if ($s1>$s2 && $f1>$f2){
                $diff=$s1->diff($f2);}
            else if ($s1<$s2 && $f1<$f2){
                $diff=$s2->diff($f1);}
            else if ($s1>$s2 && $f1<$f2){
                $diff=$s1->diff($f1);}
            else { $diff=$s2->diff($f2); }

            if ($diff->i >= $configuration->MinimumMeetTime){
                array_push($dbResult, $el);
            }
        }
        $am = [$tree->UId];
        $this->treeLevel(0,$tree->UId,$dbResult,$configuration->TreeLevel, $configuration,$result, $am);

        foreach ($result as $res){
            $count++;
            $insertData = array('TreeId' => $tree->Id, 'UId' => $res->T2UID, 'MeetType' => 1, 'PeriodStart'=>$res->T2Start, 'PeriodEnd'=>$res->T2Finish, 'Lat'=>$res->T2Lat, 'Lon'=>$res->T2Lon);
            DeviceMeetingTreeElement::updateOrCreate(['TreeId' => $tree->Id, 'UId' => $res->T2UID], $insertData);
        }

        $meetsCount = (DeviceMeetingTree::where('id', $tree->Id)->first()->TotalMeets) + $count;
        DeviceMeetingTree::where('id', $tree->Id)->update(['TotalMeets'=>$meetsCount]);
    }

    public function findMeetsByCloseContacts($configuration, $tree){
        $count = 0;
        $dbData = DB::select('
            SELECT t1.UId1 AS T1UID, t1.UId2 as T2UID,Distance,
            t1.Lat AS T1Lat, t1.Lon AS T1Lon,
            t1.DateTimeStart AS T1Start, t1.StayTime AS T1Stay, t1.DateTimeStart + INTERVAL t1.StayTime MINUTE AS T1Finish
            FROM `DeviceCloseContact` AS t1
            WHERE (t1.DateTimeStart <= "'. $configuration->EndDateTime .'" OR
                   t1.DateTimeStart + INTERVAL t1.StayTime MINUTE >= "'. $configuration->StartDateTime .'")
            AND t1.StayTime >= '.$configuration->MinimumMeetTime.'
            AND Distance <= '. $configuration->MaximumGeoRange .'
            ');
        $result = [];
        $am = [$tree->UId];
        $this->treeLevel(0,$tree->UId,$dbData,$configuration->TreeLevel, $configuration,$result, $am);

        foreach ($result as $res){
            $count++;
            $insertData = array('TreeId' => $tree->Id, 'UId' => $res->T2UID, 'MeetType' => 2, 'PeriodStart'=>$res->T1Start, 'PeriodEnd'=>$res->T1Finish, 'Lat'=>$res->T1Lat, 'Lon'=>$res->T1Lon);
            DeviceMeetingTreeElement::updateOrCreate(['TreeId' => $tree->Id, 'UId' => $res->T2UID], $insertData);
        }

        $meetsCount = (DeviceMeetingTree::where('id', $tree->Id)->first()->TotalMeets) + $count;
        DeviceMeetingTree::where('id', $tree->Id)->update(['TotalMeets'=>$meetsCount]);
        DeviceMeetingTree::where('id', $tree->Id)->update(['MeetsByMeets'=>$count]);
    }

    public function findMeetsByCheckPoints($configuration, $tree){
        $count = 0;
        $dbData = DB::select('SELECT t1.UId AS T1UID, t2.UId as T2UID, t1.CheckPointId as T1CHPTID, t2.CheckPointId as T2CHPTID,
            t1.Lat AS T1Lat, t1.Lon AS T1Lon, t2.Lat AS T2Lat, t2.Lon AS T2Lon, t1.DateTimeCheck AS T1Start, t2.DateTimeCheck AS T2Start
            FROM `DeviceCheckInOut` AS t1 JOIN `DeviceCheckInOut` AS t2
            ON t1.UId != t2.UId
            WHERE (t1.DateTimeCheck <= "'. $configuration->EndDateTime .'" AND t1.DateTimeCheck  >= "'. $configuration->StartDateTime .'")
            AND  t1.CheckPointId = t2.CheckPointId');
        $result = [];
        $am = [$tree->UId];
        $this->treeLevel(0,$tree->UId,$dbData,$configuration->TreeLevel, $configuration,$result, $am);

        foreach ($result as $res){
            $count++;
            $insertData = array('TreeId' => $tree->Id, 'UId' => $res->T2UID, 'MeetType' => 3, 'PeriodStart'=>$res->T1Start, 'PeriodEnd'=>$res->T2Start, 'Lat'=>$res->T1Lat, 'Lon'=>$res->T1Lon);
            DeviceMeetingTreeElement::updateOrCreate(['TreeId' => $tree->Id, 'UId' => $res->T2UID], $insertData);
        }

        $meetsCount = (DeviceMeetingTree::where('id', $tree->Id)->first()->TotalMeets) + $count;
        DeviceMeetingTree::where('id', $tree->Id)->update(['TotalMeets'=>$meetsCount]);
        DeviceMeetingTree::where('id', $tree->Id)->update(['MeetsByCheckPoints'=>$count]);
    }

    public function treeLevel($PUid ,$NUid, $dbResult, $level, $configuration, &$result, &$alreadyMet){
        if ($level != 0){
            foreach ($dbResult as $dd){
                if($dd->T1UID == $NUid && !in_array($dd->T2UID, $alreadyMet) && $dd->T2UID != $PUid){
                    array_push($result, $dd);
                    array_push($alreadyMet, $dd->T2UID);
                    $this->treeLevel($NUid, $dd->T2UID, $dbResult, ($level-1), $configuration, $result, $alreadyMet);
                }elseif ($dd->T1UID == $NUid){
                    $this->treeLevel($NUid, $dd->T2UID, $dbResult, ($level-1), $configuration, $result, $alreadyMet);
                }
            }
            return $result;
        }
    }
}

