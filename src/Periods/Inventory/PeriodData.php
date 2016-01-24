<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 09.07.15
 * Time: 16:12
 */
namespace AdjutantHandlers\Time\Periods\Inventory;

class PeriodData
{
    protected $from;
    protected $to;

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }


}