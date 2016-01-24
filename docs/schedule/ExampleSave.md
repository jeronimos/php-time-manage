```php

 $extendedModel = new \Models\ExtendedScheduleModel();
 $scheduleSaver = new \AdjutantHandlers\Time\Schedule\SaveSchedule($extendedModel);
 
 $datesArr = [\AdjutantHandlers\Time\Schedule\Inventory\ScheduleConsts::HOLIDAYS => "10,2,3:5;"];
 
 $scheduleSaver->saveInfo($datesArr);

```