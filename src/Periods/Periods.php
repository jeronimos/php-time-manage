<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 24.06.15
 * Time: 23:54
 */
namespace AdjutantHandlers\Time\Periods;

use AdjutantHandlers\Time\Periods\Inventory\PeriodData;

class Periods
{

    /**Allow to get days from period between dateStart and dateEnd.
     *
     * @param \DateTimeImmutable $dateStart
     * @param \DateTimeImmutable $dateEnd
     * @return array
     */
    public static function getPeriodDays(\DateTimeImmutable $dateStart, \DateTimeImmutable $dateEnd,
                                         $excludeStartDate = NULL)
    {
        $days = [];

        if ($dateStart->format("Y-m-d") === $dateEnd->format("Y-m-d")) {
            $days[] = $dateStart->format("Y-m-d");
        } else {
            $interval = new \DateInterval('P1D');
            $dateRange = new \DatePeriod($dateStart, $interval, $dateEnd->add(new \DateInterval('P1D')), $excludeStartDate);

            foreach ($dateRange as $day) {
                $days[] = $day->format("Y-m-d");
            }
        }

        return $days;
    }

    public static function getDates($startYear = NULL)
    {
        $dates = [];

        $dateTime = new \DateTime();
        $monthNumbers = 12;
        $currentYear = (int)$dateTime->format("Y");
        if (!isset($startYear)) {
            $startYear = $currentYear;
        }

        for ($i = 1; $i <= $monthNumbers && $startYear <= $currentYear; $i++) {

            $from = $startYear . "-" . $i . "-" . "01";
            $correctFrom = new \DateTime($from);
            $firstDate = new \DateTime($from);
            $secondDate = $firstDate->add(new \DateInterval("P1M"))->sub(new \DateInterval("P1D"))->format("Y-m-d");

            if ($firstDate->format("Y-m") === $dateTime->format("Y-m")) {
                break;
            }

            $date = new \StdClass();
            $date->from = $correctFrom->format("Y-m-d");
            $date->to = $secondDate;
            $dates[] = $date;

            if ($i === 12) {
                $i = 0;
                $startYear++;
            }

        }

        return $dates;
    }

    public static function getPreviousPeriod(PeriodData $period)
    {
        $previousPeriod = new \StdClass();

        $periodFrom = new \DateTimeImmutable($period->getFrom());

        $previousPeriod->from = $periodFrom->sub(new \DateInterval("P1M"))->format("Y-m-d");
        $previousPeriod->to = $periodFrom->sub(new \DateInterval("P1D"))->format("Y-m-d");

        return $previousPeriod;
    }

    public static function moveDuplicatePeriods(array $dates, array $existingPeriods)
    {
        foreach ($dates as $key => &$date) {

            foreach ($existingPeriods as $existingPeriod) {
                if ($existingPeriod->from === $date->from) {
                    unset($dates[$key]);
                    break;
                }
            }

        }

        return $dates;
    }

}