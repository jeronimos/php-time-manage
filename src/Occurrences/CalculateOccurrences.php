<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 31.03.15
 * Time: 15:04
 */
namespace AdjutantHandlers\Time\Occurrences;

use AdjutantHandlers\Converters\ToBoolean;
use AdjutantHandlers\Time\Converters\ConvertTime;
use AdjutantHandlers\Time\Occurrences\Inventory\EventData;
use AdjutantHandlers\Time\Occurrences\Inventory\Exceptions\OccurrencesException;
use AdjutantHandlers\Time\Occurrences\Inventory\OccurrenceData;
use AdjutantHandlers\Time\Occurrences\Inventory\PeriodData;
use AdjutantHandlers\Time\Occurrences\Inventory\PeriodsKeeper;

use AdjutantHandlers\Time\Occurrences\Inventory\WorkTimeData;

class CalculateOccurrences
{
    protected $model;

    /**
     * @var WorkTimeData
     */
    protected $workTime;

    /**
     * @var EventData;
     */
    protected $event;

    /**
     * @var \DateTimeImmutable
     */
    protected $workTimeNotShort;

    /**
     * @var \DateInterval
     */
    protected $originalPeriod;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getOccurrencesDates(EventData $event, WorkTimeData $workTime = NULL, PeriodsKeeper $periodsData)
    {
        $occurrencesDates = [];

        if (is_null($workTime)) {
            $workTime = new WorkTimeData();
        }

        $this->workTime = $workTime;
        $this->workTimeNotShort = clone $workTime;
        $this->event = $event;

        $periodsArr = $periodsData->getPeriods();

        foreach ($periodsArr as $period) {
            $this->originalPeriod = clone $period;
            $occurrencesDates[] = $this->calculateOccurrencesDates($period);
        }

        return $occurrencesDates;
    }

    protected function calculateOccurrencesDates($period, \DateTimeImmutable $operatingDate = NULL)
    {
        $result = NULL;

        if (!$operatingDate) {
            $operatingDate = clone $this->event->getEventDate();
            $dateIsWorkDay = $this->checkDateIsWorkDay($operatingDate);
        } else {
            $dateIsWorkDay = true;
            $this->workTime = clone $this->workTimeNotShort;
        }

        if ($dateIsWorkDay) {

            $this->checkDateIsNotNormalLengthDay($operatingDate);

            $enoughTimeState = $this->checkEnoughTime($period, $operatingDate);

            if ($enoughTimeState->checkResult) {
                $result = $this->packOccurrenceDate($enoughTimeState->occurrenceDate);
            } else {
                $residualPeriod = $this->getResidualPeriod($period, $operatingDate);
                $result = $this->recursiveCallAfterSearchNearestWorkDay($residualPeriod, $operatingDate);
            }

        } else {
            $result = $this->recursiveCallAfterSearchNearestWorkDay($period, $operatingDate);
        }

        return $result;
    }

    protected function checkEnoughTime(PeriodData $period, \DateTimeImmutable $operatingDate)
    {
        $result = new \StdClass();

        $operatingDateStart = $operatingDate->modify($this->workTime->getWorkDayStart());
        $operatingDateFinish = $operatingDate->modify($this->workTime->getWorkDayFinish());

        if ($this->event->getEventDate()->format("Y-m-d") === $operatingDate->format("Y-m-d")) {
            $dateTimeAfterEvent = $operatingDate->add($period->getPeriod());
        } else {
            $dateTimeAfterEvent = $operatingDateStart->add($period->getPeriod());
        }

        if ($dateTimeAfterEvent->format("Y-m-d H:i:s") < $operatingDateFinish->format("Y-m-d H:i:s")) {

            $result->occurrenceDate = $dateTimeAfterEvent;

            $result->checkResult = true;
        } else {
            $result->checkResult = false;
        }

        return $result;
    }

    protected function getResidualPeriod(PeriodData $periodData, \DateTimeImmutable $operatingDate)
    {
        $operatingDayStart = $operatingDate->modify($this->workTime->getWorkDayStart());
        $operatingDayFinish = $operatingDate->modify($this->workTime->getWorkDayFinish());

        $eventDate = $this->event->getEventDate();

        if ($eventDate->format("Y-m-d") === $operatingDate->format("Y-m-d")) {

            if ($eventDate->format("Y-m-d H:i:s") < $operatingDayFinish->format("Y-m-d H:i:s")) {
                $leftover = $operatingDayFinish->diff($eventDate);
                $correctedPeriod = $this->calculateResidualPeriod($periodData, $leftover);
                $periodData->setPeriod($correctedPeriod);
            }

        } elseif ($eventDate->format("Y-m-d") < $operatingDate->format("Y-m-d")) {

            $leftover = $operatingDayFinish->diff($operatingDayStart);
            $correctedPeriod = $this->calculateResidualPeriod($periodData, $leftover);
            $periodData->setPeriod($correctedPeriod);

        }

        return $periodData;
    }

    protected function calculateResidualPeriod(PeriodData $periodData, \DateInterval $leftover)
    {
        $currentPeriod = $periodData->getPeriod();

        $periodSeconds = ConvertTime::convertIntervalToSeconds($currentPeriod);
        $leftoverSeconds = ConvertTime::convertIntervalToSeconds($leftover);

        $diff = $periodSeconds - $leftoverSeconds;

        if ($diff < 0) {
            throw new OccurrencesException("Time is enough, residual period is not required.");
        }

        return new \DateInterval("PT" . $diff . "S");
    }

    protected function recursiveCallAfterSearchNearestWorkDay(PeriodData $period, \DateTimeImmutable $operatingDate = NULL)
    {
        return $this->calculateOccurrencesDates($period, $this->findNearestWorkDate($operatingDate));
    }

    protected function findNearestWorkDate(\DateTimeImmutable $operatingDate)
    {
        $result = false;

        while (!$result) {

            $checkingDate = $operatingDate->add(new \DateInterval("P1D"));
            $dateIsWorkDay = $this->checkDateIsWorkDay($checkingDate);

            $operatingDate = clone $checkingDate;

            if ($dateIsWorkDay) {
                $result = true;
            }
        }

        return $checkingDate;
    }


    protected function packOccurrenceDate(\DateTimeImmutable $occurrenceDate)
    {
        $occurrenceData = new OccurrenceData();

        $occurrenceData->setEventDate($this->event->getEventDate()->format("Y-m-d H:i:s"));
        $occurrenceData->setEventId($this->event->getEventId());
        $occurrenceData->setPeriod($this->originalPeriod);
        $occurrenceData->setOccurrenceDate($occurrenceDate);

        return $occurrenceData;
    }

    protected function checkDateIsWorkWeekend(\DateTimeImmutable $operatingDate)
    {
        $checkState = $this->model->checkDateIsWorkWeekend($operatingDate->format("Y-m-d"));
        return ToBoolean::dataToBool($checkState);
    }

    protected function checkDateIsHoliday(\DateTimeImmutable $operatingDate)
    {
        $checkState = $this->model->checkDateIsHoliday($operatingDate->format("d"), $operatingDate->format("n"));
        return ToBoolean::dataToBool($checkState);
    }

    protected function checkDateIsNotNormalLengthDay(\DateTimeImmutable $operatingDate)
    {
        $checkState = $this->model->checkDateIsNotNormalLengthDay($operatingDate->format("Y-m-d"));

        if (ToBoolean::dataToBool($checkState)) {

            if (!($checkState instanceof \StdClass)) {
                throw new OccurrencesException("Model didn't return object for not normal length day.");
            }

            $correctedWorkDayStart = new \DateTimeImmutable($checkState->date_start);
            $correctedWorkDayFinish = $correctedWorkDayStart->modify($checkState->date_finish);

            $this->workTime->setWorkDayStart($correctedWorkDayStart->format("H:i"));
            $this->workTime->setWorkDayFinish($correctedWorkDayFinish->format("H:i"));
        }

        return NULL;
    }

    protected function checkDateIsWorkDay(\DateTimeImmutable $operatingDate)
    {
        $res = NULL;

        $dayNumber = (int)$operatingDate->format("w");

        if (($dayNumber === 6) || ($dayNumber === 0)) {
            $checkWorkWeekend = $this->checkDateIsWorkWeekend($operatingDate);

            if ($checkWorkWeekend) {
                $res = true;
            } else {
                $res = false;
            }
        } else {

            $checkHoliday = $this->checkDateIsHoliday($operatingDate);

            if ($checkHoliday) {
                $res = false;
            } else {
                $res = true;
            }
        }

        return $res;
    }

} 