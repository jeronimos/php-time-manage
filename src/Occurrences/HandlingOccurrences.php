<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 25.07.15
 * Time: 20:40
 */
namespace AdjutantHandlers\Time\Occurrences;

use AdjutantHandlers\Numbers\NumbersToWords;
use AdjutantHandlers\Time\Converters\ConvertTime;
use AdjutantHandlers\Time\Occurrences\Inventory\DateIntervalsConsts;
use AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesInitData;
use AdjutantHandlers\Time\Occurrences\Inventory\PeriodData;
use AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesConsts;

class HandlingOccurrences
{
    protected $occurrencesCalculator;
    protected $initData;

    public function initCalculation(OccurrencesInitData $initData)
    {
        $this->initDat = $initData;
        $this->occurrencesCalculator = new CalculateOccurrences($initData->getModel());

        return $this->occurrencesCalculator->getOccurrencesDates(
            $initData->getEvent(), $initData->getWorkTime(), $initData->getPeriods()
        );
    }

    public function resolveOccurrences(array $occurrencesDates, $labelValues)
    {
        $result = new \StdClass();

        $currentDate = new \DateTimeImmutable();

        $allDates = [];
        foreach ($occurrencesDates as $occurrence) {
            $allDates[] = $occurrence->getOccurrenceDate();
        }

        $allDates[] = $currentDate;
        sort($allDates);

        $key = array_keys($allDates, $currentDate);
        $lastKey = count($allDates) - 1;

        switch ($key[0]) {
            case(0):
                $result->label = OccurrencesConsts::EVENT_NOT_OCCUR_YET;
                break;
            case($lastKey):
                $result->label = OccurrencesConsts::OLDER_PERIOD_LABEL;
                break;
            default:
                $previousKey = $key[0] - 1;
                $actualDate = $allDates[$previousKey];

                $occurrencePeriod = $this->getOccurrencePeriod($occurrencesDates, $actualDate);
                $result->label = $this->resolvePeriodLabel($occurrencePeriod, $labelValues);
        }

        return $result;
    }

    protected function resolvePeriodLabel(PeriodData $occurrencePeriod, $periodLabels)
    {
        $periodInHours = ConvertTime::convertIntervalToHours($occurrencePeriod->getPeriod());
        return $periodLabels[$periodInHours];
    }

    protected function getOccurrencePeriod($occurrencesDates, $actualDate)
    {
        foreach ($occurrencesDates as $occurrence) {
            if ($occurrence->getOccurrenceDate() === $actualDate) {
                $result = $occurrence->getPeriod();
            }
        }

        return $result;
    }

    public function saveOccurrencesInfo(array $occurrencesDates)
    {
    }

    public function initPeriods(array $periods, $type = DateIntervalsConsts::HOURS)
    {
        $periodsArr = [];

        foreach ($periods as &$period) {
            $period = (int)$period;
        }

        $uniquePeriods = array_unique($periods, SORT_NUMERIC);

        foreach ($uniquePeriods as $uniquePeriod) {

            $periodName = NumbersToWords::numberToNumberName($uniquePeriod);

            $name = "period" . ucfirst($periodName);
            $$name = new PeriodData();

            switch ($type):
                case(DateIntervalsConsts::HOURS):
                    $$name->setPeriod(new \DateInterval("PT" . ConvertTime::convertHoursToSeconds($uniquePeriod) . "S"));
                    break;
            endswitch;

            $periodsArr[] = $$name;
        }

        return $periodsArr;
    }

}