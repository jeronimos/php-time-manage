<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 13.03.15
 * Time: 16:34
 */

namespace AdjutantHandlers\Time\Periods;

class Months
{

    public static function getMonths($lang = 'russian')
    {
        $months = [];

        switch ($lang):
            case"russian":
                $months = [
                    1 => 'Январь',
                    2 => 'Февраль',
                    3 => 'Март',
                    4 => 'Апрель',
                    5 => 'Май',
                    6 => 'Июнь',
                    7 => 'Июль',
                    8 => 'Август',
                    9 => 'Сентябрь',
                    10 => 'Октябрь',
                    11 => 'Ноябрь',
                    12 => 'Декабрь'
                ];
                break;
        endswitch;

        return $months;

    }

} 