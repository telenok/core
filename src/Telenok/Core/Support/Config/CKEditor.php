<?php namespace Telenok\Core\Support\Config;

class CKEditor extends \App\Telenok\Core\Controller\Backend\Controller {

    protected $configView = "core::special.ckeditor.config";
    protected $configPluginWidgetInlineView = "core::special.ckeditor.plugin-widget-inline";
    
    public function getCKEditorConfig()
    {
        return view($this->configView);
    }

    public function getCKEditorPluginWidgetInline()
    {
        return view($this->configPluginWidgetInlineView);
    }
    
    public function browseFile()
    {
        $this->addJsCode(view('core::special.telenok.table')->render());

        $currentDir = 'images';
        $baseDir = public_path($currentDir);
        
        $collection = \Symfony\Component\Finder\Finder::create()
                ->in($baseDir)
                ->files()
                ->depth(0)
                ->name('/\.(png|jpg|jpeg|gif)$/');

        return view('core::special.ckeditor.browse-file', [
            'controller' => $this,
            'files' => $collection,
            'baseDir' => $baseDir,
            'currentDir' => $currentDir,
        ]);
    }

    public function browseImage()
    {
        $this->addJsCode(view('core::special.telenok.table')->render());

        return view('core::special.ckeditor.browse-image', [
            'controller' => $this
        ]);
    }
}