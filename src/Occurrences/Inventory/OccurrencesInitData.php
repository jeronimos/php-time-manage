<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 25.07.15
 * Time: 22:17
 */
namespace AdjutantHandlers\Time\Occurrences\Inventory;

use AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesModel;
use AdjutantHandlers\Time\Occurrences\Inventory\PeriodsKeeper;
use AdjutantHandlers\Time\Occurrences\Inventory\WorkTimeData;

class OccurrencesInitData
{
    /**
     * @var WorkTimeData
     */
    protected $workTime;

    /**
     * @var EventData
     */
    protected $event;

    /**
     * @var PeriodsKeeper
     */
    protected $periods;

    /**
     * @var OccurrencesModel; extended
     */
    protected $model;

    /**
     * @return WorkTimeData
     */
    public function getWorkTime()
    {
        return $this->workTime;
    }

    /**
     * @param WorkTimeData $workTime
     */
    public function setWorkTime($workTime)
    {
        $this->workTime = $workTime;
    }

    /**
     * @return EventData
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param EventData $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return PeriodsKeeper
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * @param PeriodsKeeper $periods
     */
    public function setPeriods($periods)
    {
        $this->periods = $periods;
    }

    /**
     * @return OccurrencesModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param OccurrencesModel $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }


}