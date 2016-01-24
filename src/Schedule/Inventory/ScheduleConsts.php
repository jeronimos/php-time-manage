<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 10.08.15
 * Time: 18:02
 */
namespace AdjutantHandlers\Time\Schedule\Inventory;

class ScheduleConsts
{
    const HOLIDAYS = "holidays";
    const WORK_WEEKENDS = "work_weekends";
    const NOT_NORMAL_DAYS = "not_normal_days";

    public static function getDaysTypesConsts()
    {
        return [
            self::HOLIDAYS,
            self::WORK_WEEKENDS,
            self::NOT_NORMAL_DAYS
        ];
    }

}