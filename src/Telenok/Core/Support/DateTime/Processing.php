<?php

namespace Telenok\Core\Support\DateTime;

class Processing
{
    /**
     * Return nearest minutes depend on
     *
     * @param  int  length in minutes
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function range_minutes($minutes = 'config')
    {
        $dt = \Carbon\Carbon::now()->second(0);

        $minutes = $minutes == 'config' ? config('cache.db_query.minutes', 0) : $minutes;

        if ($minutes)
        {
            return [
                $dt->minute(floor($dt->minute / $minutes) * $minutes),
                $dt->copy()->minute((floor($dt->minute / $minutes) + 1) * $minutes)
            ];
        }
        else
        {
            return [$dt, $dt->copy()];
        }
    }
}