<?php

namespace Telenok\Core\Module\Files\Browser\Wizard\Directory;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller {
                
    protected $key = 'file-browser';
    protected $parent = 'files';
    protected $icon = 'fa fa-file';

    public function processTree()
    {
        $path = trim($this->getRequest()->input('path'), '.');
        $new = trim($this->getRequest()->input('new'));
        $op = trim($this->getRequest()->input('op'));

        try 
        {
            if (!$path)
            {
                throw new \Exception($this->LL('error.path', array('dir'=>$path)));
            }

            switch ($op) {
                case 'create':
                        if (!$new)
                        {
                            throw new \Exception($this->LL('error.path', array('dir'=>$path)));
                        }

                        $this->createModelDirectory($path . DIRECTORY_SEPARATOR . $new);

                        return ['success'=>1, 'path' => $path . DIRECTORY_SEPARATOR . $new, 'id' => str_random()];
                    break;
                case 'rename':
                    break;
                case 'remove':
                    break;
            }
        } 
        catch (\Exception $exc) 
        {
            return ['error' => (array)$exc->getMessage()];
        }     
    }
    
    public function createModelDirectory($path)
    {  
        $dir = base_path() . DIRECTORY_SEPARATOR . trim($path, '\\'); 
        
        if (!\File::isDirectory($dir)) 
        {
            try
            {
                \File::makeDirectory($dir, null, true);
            } 
            catch (\Exception $e) 
            {
                throw new \Exception($this->LL('error.directory.create', array('dir' => $dir)));
            }
        }
    }     

    public function getActionParam()
    {
        return '{}';
    }

    public function getTree()
    {
        return false;
    }

    public function getListContent()
    {
        return array(
            'content' => view("core::module/files-browser.wizard-directory", array(
                    'controller' => $this,
                    'uniqueId' => str_random(),
                ))->render() 
        );
    }

    public function getTreeList($id = null)
    {
        $basePath = base_path();
        $basePathLength = \Str::length($basePath);
        
        $id = $basePath.$this->getRequest()->input('id');
        
        $listTree = [];
                
        foreach (\Symfony\Component\Finder\Finder::create()->ignoreDotFiles(true)->ignoreVCS(true)->directories()->in( $id )->depth(0) as $dir)
        { 
            $path = $dir->getPathname();

            $listTree[] = array(
                "data" => $dir->getFilename(),
                "metadata" => array('path' => substr($dir->getPathname(), $basePathLength, \Str::length($path) - $basePathLength)),
                "state" => "closed",
                "children" => [],
            );
        }
        
        if (!$this->getRequest()->input('id'))
        {
            $listTree = array(
                'data' => array(
                    "title" => "Root node", 
                    "attr" => array('id' => 'root-not-delete'), 
                ),
                "state" => "open",
                "metadata" => array('path' => '\\'),
                'children' => $listTree
            );
        }

        return $listTree;
    }
}