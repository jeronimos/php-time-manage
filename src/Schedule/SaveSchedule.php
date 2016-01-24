<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 30.03.15
 * Time: 19:45
 */
namespace AdjutantHandlers\Time\Schedule;

use AdjutantHandlers\Time\Schedule\Inventory\Exceptions\ScheduleException;
use AdjutantHandlers\Time\Schedule\Inventory\ScheduleConsts;
use AdjutantHandlers\Time\Schedule\Inventory\ScheduleModel;

class SaveSchedule
{

    protected $model;

    protected $preparedDates = [];
    protected $saveResult = [];

    protected $deleteOld;

    public function __construct(ScheduleModel $model)
    {
        $this->model = $model;
    }

    /**Method to save three type of days: ScheduleConsts::HOLIDAYS, ScheduleConsts::WORK_WEEKENDS,
     * ScheduleConsts::NOT_NORMAL_DAYS.
     *
     * Holidays have to be saved in format
     * string: "<day [,day]><colon><month>[<semicolon><day [,day]>...]"
     *
     * Other two types (workWeekends and notNormalLengthDays not implemented yet, but structure prepared for it.
     *
     * @param array $datesArr
     * @return array
     */
    public function saveInfo(array $datesArr, $deleteOld = true)
    {
        $this->deleteOld = $deleteOld;

        $this->prepareDates($datesArr);
        $this->savePreparedDates();

        return $this->saveResult;
    }

    protected function deleteScheduleInfo($tableName)
    {
        if ($this->deleteOld) {
            $this->model->deleteScheduleInfo($tableName);
        }

    }

    protected function prepareDates(array $datesArr)
    {
        $daysTypes = \AdjutantHandlers\Time\Schedule\Inventory\ScheduleConsts::getDaysTypesConsts();

        $preparedDatesArr = [];
        foreach ($datesArr as $key => $dates) {
            if (!in_array($key, $daysTypes)) {
                unset($datesArr[$key]);
            } else {
                $preparedDatesArr[$key] = explode(";", $dates);
            }
        }

        foreach ($preparedDatesArr as $dateType => &$dates) {
            switch ($dateType) {
                case(ScheduleConsts::HOLIDAYS):
                    $dates = $this->prepareHolidays($dates);
                    break;
                case(ScheduleConsts::WORK_WEEKENDS):
                    $dates = $this->prepareWorkWeekends($dates);
                    break;
                case(ScheduleConsts::NOT_NORMAL_DAYS):
                    $dates = $this->prepareNotNormalDays($dates);
                    break;
            }
        }

        $this->preparedDates = $preparedDatesArr;

        return NULL;
    }

    protected function prepareWorkWeekends($dates)
    {
    }

    protected function prepareNotNormalLengthDays($dates)
    {
    }

    protected function prepareHolidays(array $dates)
    {
        $preparedHolidays = [];

        foreach ($dates as $holidays) {

            if ($holidays === "") {
                continue;
            }

            preg_match('/([0-9, ]*)\:([0-9, ]*)/', $holidays, $matches);

            if (count($matches) !== 3) {
                throw new ScheduleException("Not correct holidays format.");
            }

            $days = $matches[1];
            $month = abs($matches[2]);

            $daysArr = explode(",", $days);

            if ($month > 12) {
                throw new ScheduleException("Month number too large.");
            }

            foreach ($daysArr as &$day) {
                $day = abs($day);

                if ($day > 31) {
                    throw new ScheduleException("Day number too large.");
                }
            }

            $preparedHolidays[$month] = $daysArr;
        }

        return $preparedHolidays;
    }

    protected function savePreparedDates()
    {
        foreach ($this->preparedDates as $dateType => $dates) {
            switch ($dateType):
                case(ScheduleConsts::HOLIDAYS):
                    $this->deleteScheduleInfo(ScheduleConsts::HOLIDAYS);
                    $this->saveResult[ScheduleConsts::HOLIDAYS] = $this->saveHolidays($dates);
                    break;
                case(ScheduleConsts::WORK_WEEKENDS):
                    $this->deleteScheduleInfo(ScheduleConsts::WORK_WEEKENDS);
                    $this->saveResult[ScheduleConsts::WORK_WEEKENDS] = $this->saveWorkWeekends($dates);
                    break;
                case(ScheduleConsts::NOT_NORMAL_DAYS):
                    $this->deleteScheduleInfo(ScheduleConsts::NOT_NORMAL_DAYS);
                    $this->saveResult[ScheduleConsts::NOT_NORMAL_DAYS] = $this->saveNotNormalDays($dates);
                    break;
            endswitch;
        }

        return NULL;
    }

    protected function saveHolidays(array $dates)
    {
        foreach ($dates as $month => $holidays) {
            foreach ($holidays as $day) {

                $resInfo = new \StdClass();
                $resInfo->day = $day;
                $resInfo->month = $month;
                $resInfo->resState = $this->model->saveHoliday((int)$day, (int)$month);

                $result[] = $resInfo;
            }
        }

        return $result;
    }

    protected function saveWorkWeekends()
    {
    }

    protected function saveNotNormalDays()
    {
    }

} 