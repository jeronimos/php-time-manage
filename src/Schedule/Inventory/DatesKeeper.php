<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 02.08.15
 * Time: 18:10
 */
namespace AdjutantHandlers\Time\Schedule\Inventory;

class DatesKeeper
{
    protected $holidays;
    protected $workWeekends;
    protected $notNormalDays;

    /**
     * @return mixed
     */
    public function getHolidays()
    {
        return $this->holidays;
    }

    /**
     * @param mixed $holidays
     */
    public function setHolidays($holidays)
    {
        $this->holidays = $holidays;
    }

    /**
     * @return mixed
     */
    public function getWorkWeekends()
    {
        return $this->workWeekends;
    }

    /**
     * @param mixed $workWeekends
     */
    public function setWorkWeekends($workWeekends)
    {
        $this->workWeekends = $workWeekends;
    }

    /**
     * @return mixed
     */
    public function getNotNormalDays()
    {
        return $this->notNormalDays;
    }

    /**
     * @param mixed $notNormalDays
     */
    public function setNotNormalDays($notNormalDays)
    {
        $this->notNormalDays = $notNormalDays;
    }


}