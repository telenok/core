<?php

namespace Telenok\Core\Module\Files\Browser\Wizard\Directory;

/**
 * @class Telenok.Core.Module.Files.Browser.Wizard.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTab.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTab\Controller
{
    protected $key = 'file-browser';
    protected $parent = 'files';
    protected $icon = 'fa fa-file';

    public function processTree()
    {
        $path = trim($this->getRequest()->input('path'), '.');
        $new = trim($this->getRequest()->input('new'));
        $op = trim($this->getRequest()->input('op'));

        try {
            if (!$path) {
                throw new \Exception($this->LL('error.path', ['dir' => $path]));
            }

            switch ($op) {
                case 'create':
                    if (!$new) {
                        throw new \Exception($this->LL('error.path', ['dir' => $path]));
                    }

                    $this->createModelDirectory($path.DIRECTORY_SEPARATOR.$new);

                    return ['success' => 1, 'path' => $path.DIRECTORY_SEPARATOR.$new, 'id' => str_random()];
                    break;
                case 'rename':
                    break;
                case 'remove':
                    break;
            }
        } catch (\Exception $exc) {
            return ['error' => (array) $exc->getMessage()];
        }
    }

    public function createModelDirectory($path)
    {
        $dir = base_path().DIRECTORY_SEPARATOR.trim($path, '\\');

        if (!\File::isDirectory($dir)) {
            try {
                \File::makeDirectory($dir, null, true);
            } catch (\Exception $e) {
                throw new \Exception($this->LL('error.directory.create', ['dir' => $dir]));
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
        return [
            'content' => view('core::module/files-browser.wizard-directory', [
                'controller' => $this,
                'uniqueId'   => str_random(),
            ])->render(),
        ];
    }

    public function getTreeList($id = null)
    {
        $basePath = base_path();
        $basePathLength = \Str::length($basePath);

        $id = $basePath.$this->getRequest()->input('id');

        $listTree = [];

        foreach (\Symfony\Component\Finder\Finder::create()->ignoreDotFiles(true)->ignoreVCS(true)->directories()->in($id)->depth(0) as $dir) {
            $path = $dir->getPathname();

            $listTree[] = [
                'data'     => $dir->getFilename(),
                'metadata' => ['path' => substr($dir->getPathname(), $basePathLength, \Str::length($path) - $basePathLength)],
                'state'    => 'closed',
                'children' => [],
            ];
        }

        if (!$this->getRequest()->input('id')) {
            $listTree = [
                'data' => [
                    'title' => 'Root node',
                    'attr'  => ['id' => 'root-not-delete'],
                ],
                'state'    => 'open',
                'metadata' => ['path' => '\\'],
                'children' => $listTree,
            ];
        }

        return $listTree;
    }
}
