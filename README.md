# PHP time manage
Manage time periods, occurrences after events (work hours after event), schedule, etc.

Please, note that package stability is dev. 

###Occurrences module

It allow to calculate time periods according to work schedule. 

E.g., your work day start at 10am and finish at 7pm. Event occur at 6pm on Friday. And you want to calculate when will occur 2 and 5 work hours after event. If Saturday and Sunday is not work weekends, and Friday has usual timing, and Monday is not holiday, occurrences dates will be Monday, 11am and 2pm.

As result it return dates and can resolve it to labels, allowing to backlight (by setting class name) this event in different colors depend on wich period is occured now (e.g., yellow, red).

You just should create three table in DB - holidays, work weekends and not normal days (shorter or longer that normal). Fil it depend on your neeeds, so script can consider it. You can find alter script [here](docs/schedule/schedule.sql). Example of extended schedule model [here](docs/schedule/ExtendedModelExample.md). Example of save holidays [here](docs/schedule/ExampleSave.md). Other types is under development, but you can add it directly to DB.

```php

    $orderId = 55145;
    $incomeDate = "2015-08-07 18:00:00";
    
    $handlingOccurrences = new \AdjutantHandlers\Time\Occurrences\HandlingOccurrences();
    
    $occurrencesInitData = new \AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesInitData();
    
    $eventData = new \AdjutantHandlers\Time\Occurrences\Inventory\EventData();
    $eventData->setEventId((int)$orderId);
    $eventData->setEventDate(new \DateTimeImmutable($incomeDate));
    
    $periodsKeeper = new \AdjutantHandlers\Time\Occurrences\Inventory\PeriodsKeeper();
    
    $periodOne = 2;
    $periodTwo = 5;
    $labelValues = [
        $periodOne => \AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesConsts::MIN_PERIOD_LABEL,
        $periodTwo => \AdjutantHandlers\Time\Occurrences\Inventory\OccurrencesConsts::OLDER_PERIOD_LABEL
    ];
    
    $periods = [$periodOne, $periodTwo];
    $periodsKeeper->setPeriods($handlingOccurrences->initPeriods($periods));
    
    $occurrencesInitData->setEvent($eventData);
    $occurrencesInitData->setPeriods($periodsKeeper);
    
    //your model, that has connection to your db, and that extends from OccurrencesModel (contain queries)
    $model = new \Models\ExtendedOccurrencesModel();
    $occurrencesInitData->setModel($model);
    
    try {
        $occurrencesDates = $handlingOccurrences->initCalculation($occurrencesInitData);
        $resultLabel = $handlingOccurrences->resolveOccurrences($occurrencesDates, $labelValues);
    
    } catch (\AdjutantHandlers\Time\Occurrences\Inventory\Exceptions\OccurrencesException $e) {
    }
    
    return $resultLabel;
```    

Model, connected to DB can look like this [one](docs/occurrences/ExtendedOccurrencesModel.md
)
