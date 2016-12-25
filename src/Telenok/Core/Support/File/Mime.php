<?php

namespace Telenok\Core\Support\File;

class Mime
{
    public static function type($file)
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