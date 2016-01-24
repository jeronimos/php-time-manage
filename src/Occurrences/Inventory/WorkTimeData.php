<?php
namespace AdjutantHandlers\Time\Occurrences\Inventory;

class WorkTimeData
{
    protected $workDayStart = OccurrencesConsts::WORK_DAY_START;
    protected $workDayFinish = OccurrencesConsts::WORK_DAY_FINISH;

    /**
     * @return mixed
     */
    public function getWorkDayFinish()
    {
        return $this->workDayFinish;
    }

    /**
     * @param mixed $workDayFinish
     */
    public function setWorkDayFinish($workDayFinish)
    {
        $this->workDayFinish = $workDayFinish;
    }

    /**
     * @return mixed
     */
    public function getWorkDayStart()
    {
        return $this->workDayStart;
    }

    /**
     * @param mixed $workDayStart
     */
    public function setWorkDayStart($workDayStart)
    {
        $this->workDayStart = $workDayStart;
    }


} 