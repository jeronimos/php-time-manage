<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 25.07.15
 * Time: 22:35
 */
namespace AdjutantHandlers\Time\Occurrences\Inventory;

class OccurrencesModel
{

    public function checkDateIsWorkWeekend($weekendDate)
    {
        return "
        SELECT w.weekend_date
        FROM schedule_work_weekends AS w
        WHERE w.weekend_date = \"{$weekendDate}\"
        ";
    }

    public function checkDateIsNotNormalLengthDay($shortDate)
    {
        return "
        SELECT n.date_start, n.date_finish
        FROM schedule_not_normal_days AS n
        WHERE n.date_start LIKE \"%{$shortDate}%\"
        ";
    }

    public function checkDateIsHoliday($day, $month)
    {
        return "
        SELECT h.day
        FROM schedule_holidays AS h
        WHERE h.day = \"{$day}\" AND h.month = \"{$month}\"
        ";

    }


}