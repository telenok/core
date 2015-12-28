<?php

if (!function_exists('range_minutes')) 
{
    /**
     * Return nearest minutes depend on 
     *
     * @param  int  length in minutes
     * @return string
     *
     * @throws \RuntimeException
     */
    function range_minutes($minutes = 'config')
    {
        $dt = \Carbon\Carbon::now();

        $minutes = $minutes == 'config' ? config('cache.db_query.minutes', 0) : $minutes;

        if ($minutes)
        {
            return [
                $dt->minute(floor($dt->minute/$minutes) * $minutes),
                $dt->minute(ceil($dt->minute/$minutes) * $minutes)
            ];
        }
        else 
        {
            return [$dt, $dt];
        }
    }
}