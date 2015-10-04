<?php

namespace Telenok\Core\Module\Tools\PhpConsole;

class Controller extends \Telenok\Core\Interfaces\Presentation\Simple\Controller {

    protected $key = 'php-console';
    protected $parent = 'tools';
    protected $icon = 'fa fa-file';

    public function processCode()
    {
        $dir = storage_path('telenok/tmp/php-console');
        $file = $dir . '/' . str_random(6) . '.php'; 
        
        try
        {
            \File::makeDirectory($dir, 0775, true, true);
        } 
        catch (\Exception $exc) {}
        
        
        $finder = \Symfony\Component\Finder\Finder::create()
                ->in($dir)->date('2 days ago')->files();
        
        foreach ($finder as $file) 
        {
            @unlink($file->getRealpath());
        }

        
        file_put_contents($file, $this->getRequest()->get('content'));

        if (file_exists($file))
        {
            try
            {
                ob_start();

                include $file;

                $result = ob_get_contents();

                ob_end_clean();
            } 
            catch (\Exception $exc)
            {
                return ['result' => ($exc->getTraceAsString())];
            }
            
            @unlink($file);
            
            return ['result' => ($result)];
        }
        else
        {
            return ['result' => 'Error! Cant create file "' . $file . '" to process code. Sorry'];
        }
    }
}
