<?php

namespace Telenok\Core\Support\CKEditor;

/**
 * @class Telenok.Core.Support.Config.CKEditor
 * Support rich text editor caller CKEditor.
 */
class Controller extends \App\Vendor\Telenok\Core\Controller\Backend\Controller {

    protected $key = 'ckeditor';
    protected $configView = "core::special.ckeditor.config";
    protected $languageDirectory = 'support';
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

        return view('core::special.ckeditor.browse-file', [
            'controller' => $this,
            'jsUnique' => str_random(),
        ]);
    }

    public function browseImage()
    {
        $this->addJsCode(view('core::special.telenok.table')->render());

        return view('core::special.ckeditor.browse-image', [
            'controller' => $this,
            'jsUnique' => str_random(),
        ]);
    }

    public function createCache($path, $width = 0, $height = 0, $action = '')
    {
        $job = new \App\Vendor\Telenok\Core\Jobs\Cache\Store([
            'path' => $path,
            'path_cache' => $this->pathCache($path, $width, $height, $action),
            'storage_key' => app('filesystem')->getDefaultDriver(),
            'storage_cache_key' => app('filesystem')->getDefaultDriver(),
            'width' => $width,
            'height' => $height,
            'action' => $action,
        ]);

        if (config('image.cache.queue'))
        {
            $job->onQueue(\App\Vendor\Telenok\Core\Jobs\Cache\Store::QUEUES_CACHE);

            $this->dispatch($job);
        }
        else
        {
            $job->handle();
        }
    }

    public function isImage($file)
    {
        return in_array(pathinfo($file, PATHINFO_EXTENSION), \App\Vendor\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION, true);
    }

    public function existsCache($filename = '', $width = 0, $height = 0, $action = '')
    {
        $filename = $this->filenameCache($filename, $width, $height, $action);

        return \App\Vendor\Telenok\Core\Support\File\StoreCache::existsCache(app('filesystem')->getDefaultDriver(), $filename);
    }

    public function pathCache($filename = '', $width = 0, $height = 0, $action = '')
    {
        return \App\Vendor\Telenok\Core\Support\File\StoreCache::pathCache($filename, $width, $height, $action);
    }

    public function filenameCache($filename = '', $width = 0, $height = 0, $action = '')
    {
        return \App\Vendor\Telenok\Core\Support\File\StoreCache::filenameCache($filename, $width, $height, $action);
    }

    public function urlCache($path, $width = 0, $height = 0, $action = '')
    {
        $urlPattern = config("filesystems.disks." . app('filesystem')->getDefaultDriver() . ".retrieve_url");

        if ($urlPattern instanceof \Closure)
        {
            return $urlPattern($path, $width, $height, $action);
        }
        else
        {
            return trim($urlPattern, '\\/') . '/' .
                    \App\Vendor\Telenok\Core\Support\File\StoreCache::pathCache($path, $width, $height, $action);
        }
    }

    public function storageDirectoryList()
    {
        return app('filesystem')->allDirectories($this->getRootDirectory());
    }

    public function storageFileList()
    {
        $path = $this->getRequest()->input('directory', '');

        if ($path && strpos('..', $path) !== FALSE)
        {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('"wrong directory"');
        }

        $files = collect(app('filesystem')->files($this->getRootDirectory() . '/' . $path));

        if ($this->getRequest()->input('file_type') == 'image')
        {
            $files = $files->filter(function($item)
            {
                return in_array(pathinfo($item, PATHINFO_EXTENSION), \App\Vendor\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION, true);
            });
        }

        return view('core::special.ckeditor.file-storage', [
            'controller' => $this,
            'files' => $files,
            'allowNew' => $this->getRequest()->input('allow_new'),
            'allowBlob' => $this->getRequest()->input('allow_blob'),
            'jsUnique' => $this->getRequest()->input('jsUnique'),
        ]);
    }

    public function modelFileList()
    {
        $name = $this->getRequest()->input('name');

        $files = \App\Vendor\Telenok\Core\Model\File\File::active()
                    ->withPermission()
                    ->where(function($query)
                    {
                        $query->where(app('db')->raw(1), 0);

                        if ($this->getRequest()->input('file_type'))
                        {
                            foreach (\App\Vendor\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION as $ext)
                            {
                                $query->orWhere('upload_file_name', 'LIKE', '%.' . $ext . '%');
                            }
                        }
                    })
                    ->where('title', 'LIKE', '%' . $name . '%')
                    ->take(51)->get();

        return view('core::special.ckeditor.file-model', [
            'controller' => $this,
            'files' => $files,
            'allowNew' => $this->getRequest()->input('allow_new'),
            'allowBlob' => $this->getRequest()->input('allow_blob'),
            'jsUnique' => $this->getRequest()->input('jsUnique'),
        ]);
    }

    public function modalCropperContent()
    {
        $cropper = new \Telenok\Core\Support\Image\Cropper();

        $cropper->setPath($this->getRequest()->input('file_url'));
        $cropper->setAllowNew((int) $this->getRequest()->input('allow_new'));
        $cropper->setAllowBlob((int) $this->getRequest()->input('allow_blob'));
        $cropper->setJsUnique($this->getRequest()->input('js_unique'));

        return $cropper->getContent();
    }

    public function imageCreate()
    {
        $base64 = $this->getRequest()->input('blob');
        $mime = $this->getRequest()->input('mime');
        $directory = $this->getRootDirectory() . '/' . $this->getRequest()->input('directory', date('Y/m/') . ceil(date('d') / 7));

        if (strpos('..', $directory) !== FALSE)
        {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('"wrong directory"');
        }

        switch ($mime)
        {
            case 'image/jpeg':
                $extension = 'jpg';
                break;
            case 'image/gif':
                $extension = 'gif';
                break;
            case 'image/png':
                $extension = 'png';
                break;

            default:
                throw new \Exception('Wrong mime type');
        }

        $filepath = $directory . '/' . ($name = (str_random(6) . '.' . $extension));

        app('filesystem')->makeDirectory($directory);
        app('filesystem')->put($filepath, base64_decode(explode(',', $base64)[1]), \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC);

        return [
            'src' => $filepath,
            'filename' => $name
        ];
    }

    public function directoryCreate()
    {
        $directory = $this->getRootDirectory() . '/' . $this->getRequest()->input('directory');
        $name = $this->getRequest()->input('name');

        if (strpos('..', $directory) !== FALSE || preg_match("/\W/", $name))
        {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('"wrong directory"');
        }

        app('filesystem')->makeDirectory($directory . '/' . $name);

        return [
            'directory' => ( ($d = $this->getRequest()->input('directory')) ? $d . '/' : '') . $name,
        ];
    }

    public function uploadFile()
    {
        $file = $this->getRequest()->file('file');
        $directory = $this->getRootDirectory() . '/' . $this->getRequest()->input('directory');

        $extension = $file->getClientOriginalExtension() ? : $file->guessExtension();

        if (!in_array($extension, \App\Vendor\Telenok\Core\Support\File\Processing::SAFE_EXTENSION, true))
        {
            throw new \Exception($this->LL('error.extension', ['attribute' => $extension]));
        }

        if (strpos('..', $directory) !== FALSE)
        {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('"wrong directory"');
        }

        if ($file->isValid())
        {
            $file->move(public_path($directory), pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    . '_' . str_random(3) . '.' . $file->getClientOriginalExtension());
        }

        return ['success' => 1];
    }

    public function getRootDirectory()
    {
        return config('filesystems.upload.public');
    }

}
