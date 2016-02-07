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

if (!function_exists('file_mime_type'))
{
    function file_mime_type($file)
    {
        if (function_exists('finfo_file'))
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $file);
            finfo_close($finfo);
        }
        
        if (!$type || in_array($type, array('application/octet-stream', 'text/plain')))
        {
            $secondOpinion = exec('file -b --mime-type -m /usr/share/misc/magic ' . escapeshellarg($file), $foo, $returnCode);

            if ($returnCode === 0 && $secondOpinion)
            {
                $type = $secondOpinion;
            }
        }

        if (!$type || in_array($type, array('application/octet-stream', 'text/plain')))
        {
            $exifImageType = exif_imagetype($file);
            
            if ($exifImageType !== false)
            {
                $type = image_type_to_mime_type($exifImageType);
            }
        }

        return $type;
    }
}

if (!function_exists('theme_view'))
{
    function theme_view($view = null, $data = [], $mergeData = [])
    {
        return \App\Telenok\Core\Support\Config\Theme::view($view, $data, $mergeData);
    }
}