<?php

namespace App\Helpers;

use Carbon\Carbon;

class WorkdayHelper
{
    /**
     * Retorna el próximo día laborable (no fin de semana).
     */
    public static function getNextBusinessDay(): string
    {
        $date = Carbon::now()->addDay();
        while ($date->isWeekend()) {
            $date->addDay();
        }
        return $date->format('Y-m-d');
    }

    /**
     * Retorna el último día laborable (no fin de semana).
     */
    public static function getLastBusinessDay(): string
    {
        $date = Carbon::now()->subDay();
        while ($date->isWeekend()) {
            $date->subDay();
        }
        return $date->format('Y-m-d');
    }
}
