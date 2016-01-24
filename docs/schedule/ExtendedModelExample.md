```php

<?php

use AdjutantHandlers\Time\Schedule\Inventory;

class AdminScheduleModel extends ScheduleModel
{
    protected $mysqli;
    
    public function __construct()
    {
        $this->mysqli = MySQLConnection::getConnection();
    }

    public function saveHoliday($day, $month)
    {
        return parent::saveHoliday($day, $month, $this->mysqli);
    }


    public function getHolidays()
    {
        $query = parent::getHolidays();
        return $this->mysqli->query($query)->fetch_object();
    }

    public function deleteScheduleInfo($tableName)
    {
        $query = parent::deleteScheduleInfo($tableName);
        return $this->mysqli->query($query);
    }

    public function saveWorkWeekend($date){}
    public function saveNotNormalDay($dateStart, $dateFinish){}


}

```
