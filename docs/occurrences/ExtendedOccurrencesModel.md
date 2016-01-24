```php
<?php
namespace Models;

use AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesModel;

class ExtendedOccurrencesModel extends OccurrencesModel
{
    protected $mysqli;
    
    public function __construct()
    {
        $this->mysqli = MySQLConnection::getConnection();
    }

    public function checkDateIsWorkWeekend($weekendDate)
    {
        $query = parent::checkDateIsWorkWeekend($weekendDate);
        return $this->mysqli->query($query)->fetch_object();
    }

    public function checkDateIsNotNormalLengthDay($notNormalLengthDay)
    {
        $query = parent::checkDateIsNotNormalLengthDay($notNormalLengthDay);
        return $this->mysqli->query($query)->fetch_object();
    }

    public function checkDateIsHoliday($day, $month)
    {
        $query = parent::checkDateIsHoliday($day, $month);
        return $this->mysqli->query($query)->fetch_object();
    }

}

```
