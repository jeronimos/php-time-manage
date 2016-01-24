<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.08.15
 * Time: 18:53
 */

namespace AdjutantHandlers\Time\Schedule\Inventory;

class ScheduleModel
{
    const HOLIDAYS = "schedule_holidays";
    const WORK_WEEKENDS = "schedule_work_weekends";
    const NOT_NORMAL_DAYS = "schedule_not_normal_days";

    public function saveHoliday($day, $month, $mysqli = NULL)
    {
        $stmt = $mysqli->prepare("INSERT INTO schedule_holidays (day, month) VALUES (?, ?)");
        $stmt->bind_param('ii', $day, $month);

        $res = $stmt->execute();

        return $res;
    }

    public function saveWorkWeekend($date)
    {
    }

    public function saveNotNormalDay($dateStart, $dateFinish)
    {
    }

    public function getHolidays()
    {
        return "SELECT day, month
     FROM schedule_holidays
     ORDER BY month ASC
     ";
    }

    public function deleteScheduleInfo($tableName)
    {
        $tableName = "schedule_" . $tableName;

        return "
        DELETE FROM $tableName
        WHERE id > 0;
        ";
    }


}