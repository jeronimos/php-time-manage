<?php
namespace AdjutantHandlers\Time\Occurrences\Inventory;

class PeriodData
{
    /**
     * @var \DateInterval
     */
    protected $period;

    /**
     * @return \DateInterval
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param \DateInterval $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }


}