<?php
namespace AdjutantHandlers\Time\Occurrences\Inventory;


class PeriodsKeeper
{

    /**
     * @var array
     */
    protected $periods;

    /**
     * @return array
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * @param array $periods
     */
    public function setPeriods($periods)
    {
        $this->periods = $periods;
    }


}