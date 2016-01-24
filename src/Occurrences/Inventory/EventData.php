<?php
namespace AdjutantHandlers\Time\Occurrences\Inventory;

class EventData
{

    /**
     * @var \DateTimeImmutable
     */
    protected $eventDate;

    /**
     * @var int
     */
    protected $eventId;

    /**
     * @return \DateTimeImmutable
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * @param \DateTimeImmutable $eventDate
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }


}