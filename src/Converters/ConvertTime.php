<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 01.04.15
 * Time: 14:56
 */
namespace AdjutantHandlers\Time\Converters;

class ConvertTime
{

    public static function convertHoursToSeconds($hours)
    {
        return ($hours * 60 * 60);
    }

    public static function convertIntervalToSeconds(\DateInterval $interval)
    {
        return $interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s;
    }

    public static function convertIntervalToHours(\DateInterval $interval)
    {
        return $interval->days * 24 + $interval->h + $interval->i / 60 + $interval->s / 3600;
    }


} 