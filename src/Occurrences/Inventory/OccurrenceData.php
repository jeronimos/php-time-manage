<?php
namespace AdjutantHandlers\Time\Occurrences\Inventory;

class OccurrenceData
{

    protected $eventDate;
    protected $eventId;
    protected $period;
    protected $occurrenceDate;

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param mixed $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * @param mixed $eventDate
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @return mixed
     */
    public function getOccurrenceDate()
    {
        return $this->occurrenceDate;
    }

    /**
     * @param mixed $occurrenceDate
     */
    public function setOccurrenceDate($occurrenceDate)
    {
        $this->occurrenceDate = $occurrenceDate;
    }


} 