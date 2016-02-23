<?php 

namespace Telenok\Core\Support\Config;

/**
 * @class Telenok.Core.Support.Config.Theme
 * Add support themes for view.
 */
class Theme {

    public static function selectActiveTheme()
    {
        static $activeTheme = null;

        if ($activeTheme === null)
        {
            $themeConfig = collect(config('telenok.view.theme'));

            var_dump($themeConfig->all());

            foreach($themeConfig->get('key') as $k => $val)
            {
                $keys = (array)$themeConfig->get('key');
                $cases = (array)$themeConfig->get('case');
                $values1 = (array)$themeConfig->get('value1');
                $values2 = (array)$themeConfig->get('value2');
                
                $key = array_get($keys, $k, null);
                $case = array_get($cases, $k, null);
                $value1 = array_get($values1, $k, null);
                $value2 = array_get($values2, $k, null);

                if ($key && $case)
                {
                    dd( static::processDefault($key, $case, $value1, $value2) );
                    
                    if ($case == 'url-regex' && static::processUrlRegexp($key, $case, $value1, $value2))
                    {
                        $activeTheme = $key;
                    }
                    elseif ($case == 'time-range' && static::processTimeRange($key, $case, $value1, $value2))
                    {
                        $activeTheme = $key;
                    }
                    elseif ($case == 'date-range' && static::processDateRange($key, $case, $value1, $value2))
                    {
                        $activeTheme = $key;
                    }
                    elseif ($case == 'php' && static::processPhp($key, $case, $value1, $value2))
                    {
                        $activeTheme = $key;
                    }                    
                    elseif ($case == 'default' && static::processDefault($key, $case, $value1, $value2))
                    {
                        $activeTheme = $key;
                    }                    
                }
            }

            if ($activeTheme === null)
            {
                return $activeTheme = '';
            }
            else
            {
                app('translator')->addNamespace(base_path('resources/views/template/' . $activeTheme . '/lang'));
                
                return $activeTheme;
            }
        }
    }

    public static function processUrlRegexp($key, $case, $value1, $value2)
    {
        return preg_match($value1, app('request')->url());
    }

    public static function processTimeRange($key, $case, $value1, $value2)
    {
        $now = \Carbon\Carbon::now(config('app.timezone'))->secondsSinceMidnight();

        $time1 = \Carbon\Carbon::createFromFormat("H:i", $value1)->secondsSinceMidnight();
        $time2 = \Carbon\Carbon::createFromFormat("H:i", $value2)->secondsSinceMidnight();

        return ($now - $time1 >= 0) && ($time2 - $now >= 0);
    }

    public static function processDateRange($key, $case, $value1, $value2)
    {
        $tz = config('app.timezone');
        $now = \Carbon\Carbon::now($tz);
        $time1 = \Carbon\Carbon::createFromFormat("Y-m-d H:i", $value1, $tz);
        $time2 = \Carbon\Carbon::createFromFormat("Y-m-d H:i", $value2, $tz);

        return $now->between($time1, $time2);
    }

    public static function processPhp($key, $case, $value1, $value2)
    {
        $dir = storage_path('telenok/tmp/php-template-key');
        $file = $dir . '/' . str_random(6) . '.php';

        if (!is_dir($dir))
        {
            \File::makeDirectory($dir, 0775, true, true);
        }

        file_put_contents($file, '<?php ' . $value1, LOCK_EX);

        if (file_exists($file))
        {
            return include($file);
        }
        else
        {
            throw new \Exception('Error! Cant create file "' . $file . '" to process code. Sorry');
        }
    }

    public static function processDefault($key, $case, $value1, $value2)
    {
        return true;
    }

    public static function view($view = null, $data = [], $mergeData = [])
    {
        $activeTheme = static::selectActiveTheme();
                
        if ($view)
        {
            if (strpos($view, '::') !== FALSE && view()->exists($v = str_replace('::', '::' . $activeTheme . '/views', $view)))
            {
                return view($v, $data, $mergeData);
            }
            else if (view()->exists($v = 'template/' . $activeTheme . '/views/' . $view))
            {
                return view($v, $data, $mergeData);
            }
        }

        return view($view, $data, $mergeData);
    }
}