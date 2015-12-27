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
    function range_minutes($minutes)
    {
        $dt = \Carbon\Carbon::now();

        if ($minutes)
        {
            return $dt->minute(floor($dt->minute/$minutes) * $minutes);
        }
        else 
        {
            return $dt;
        }
    }
}