<?php

namespace Telenok\Core\Field\Upload;

/**
 * @class Telenok.Core.Field.Upload.File
 * Class store internal data of field with key "upload".
 *
 * @mixin Illuminate.Queue.InteractsWithQueue
 * @mixin Illuminate.Queue.SerializesModels
 * @mixin Illuminate.Foundation.Bus.DispatchesJobs
 */
class File
{
    use \Illuminate\Queue\InteractsWithQueue,
        \Illuminate\Queue\SerializesModels,
        \Illuminate\Foundation\Bus\DispatchesJobs;

    /**
     * @protected
     *
     * @property {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @member Telenok.Core.Field.Upload.File
     */
    protected $model;

    /**
     * @property {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @member Telenok.Core.Field.Upload.File
     */
    protected $field;

    /**
     * @property {Illuminate.Contracts.Filesystem.Filesystem} $disk
     * Object of disk.
     * @member Telenok.Core.Field.Upload.File
     */
    protected $disk;

    /**
     * @property {Illuminate.Contracts.Filesystem.Filesystem} $diskCache
     * Object of disk where stored cached data.
     * @member Telenok.Core.Field.Upload.File
     */
    protected $diskCache;

    /**
     * @property {String} $storageKey
     * Key of storage's disk from config
     * @member Telenok.Core.Field.Upload.File
     */
    protected $storageKey;

    /**
     * @property {String} $storageCacheKey
     * Key of storage's disk of cache from config
     * @member Telenok.Core.Field.Upload.File
     */
    protected $storageCacheKey;

    /**
     * @constructor
     * Initialize internal data
     *
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field}                $field
     * @member Telenok.Core.Field.Upload.File
     */
    public function __construct($model, $field)
    {
        $this->field = $field;
        $this->model = $model;

        $this->initDisk();
        $this->initDiskCache();
    }

    /**
     * @method downloadStreamLink
     * Return url for downloading file via stream.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function downloadStreamLink()
    {
        return route('telenok.download.stream.file', ['modelId' => $this->model->id, 'fieldId' => $this->field->id]);
    }

    /**
     * @method downloadImageLink
     * Return url for downloading image via stream.
     *
     * @param {Integer} $width
     *                          Width of image. Can be 0.
     * @param {Integer} $height
     *                          Height of image. Can be 0.
     * @param {String}  $action
     *                          One of constant's like TODO_RESIZE and TODO_RESIZE_PROPORTION from {Telenok.Core.Support.Image.Processing}.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function downloadImageLink($width = 0, $height = 0, $action = \App\Vendor\Telenok\Core\Support\Image\Processing::TODO_RESIZE)
    {
        if (!$this->existsCache($width, $height, $action)) {
            $this->createCache($width, $height, $action);
        }

        if (\App\Vendor\Telenok\Core\Security\Acl::subjectAny(['user_any', 'user_unauthorized'])
                        ->can('read', [$this->model, 'object_field.'.$this->model->getTable().'.'.$this->field->code])) {
            $urlPattern = config("filesystems.disks.{$this->getStorageCacheKey()}.retrieve_url");

            if ($urlPattern instanceof \Closure) {
                return $urlPattern($this->filename(), $width, $height, $action);
            } else {
                return trim($urlPattern, '\\/').'/'.$this->pathCache($width, $height, $action);
            }
        } else {
            return route('telenok.download.image.file', [
                'modelId' => $this->model->id,
                'fieldId' => $this->field->id,
                'toDo'    => $action,
                'width'   => (int) $width,
                'height'  => (int) $height,
            ]);
        }
    }

    /**
     * @method content
     * Return original name of stored file in model.
     *
     * @param {String} $path
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function content($path = '')
    {
        return $this->disk()->get($path ?: $this->path());
    }

    /**
     * @method initDisk
     * Choose random disk and set $storageKey of it and set $disk value.
     *
     * @return {void}
     * @member Telenok.Core.Field.Upload.File
     */
    public function initDisk()
    {
        $uploadStorages = \App\Vendor\Telenok\Core\Support\File\Store::storageList(array_map('trim', explode(',', config('filesystems.upload_storages'))))->all();

        $storages = \App\Vendor\Telenok\Core\Support\File\Store::storageList(json_decode($this->field->upload_storage, true));

        $storageKey = $storages->shuffle()->first(function ($v, $k) use ($uploadStorages) {
            if (in_array($v, $uploadStorages, true) && app('filesystem')->disk($v)->exists($this->path())) {
                return true;
            }
        });

        if (!$storageKey) {
            $storageKey = $storages->shuffle()->first();
        }

        $this->storageKey = $storageKey;
        $this->disk($storageKey);
    }

    /**
     * @method initDiskCache
     * Choose random cache disk and set $storageCacheKey of it and set $diskCache value.
     *
     * @return {void}
     * @member Telenok.Core.Field.Upload.File
     */
    public function initDiskCache()
    {
        $logic = config('filesystems.cache.logic_storage');

        $cacheStorages = \App\Vendor\Telenok\Core\Support\File\Store::storageList($logic($this->filename()));
        $storages = \App\Vendor\Telenok\Core\Support\File\Store::storageList(array_map('trim', explode(',', config('cache.cache_storages'))));

        $storageKey = $cacheStorages->first(function ($v, $k) {
            if ($this->filename() && app('filesystem')->disk($v)->exists($this->pathCache())) {
                return true;
            }
        });

        if (!$storageKey) {
            $storageKey = $storages->first();
        }

        $this->storageCacheKey = $storageKey;
        $this->diskCache($storageKey);
    }

    /**
     * @method disk
     * Initialize storage.
     *
     * @param {String} $storageKey
     *                             Key of disk from config.
     *
     * @return {void}
     * @member Telenok.Core.Field.Upload.File
     */
    public function disk($storageKey = '')
    {
        if ($storageKey) {
            $this->disk = app('filesystem')->disk($storageKey);
        }

        return $this->disk;
    }

    /**
     * @method diskCache
     * Initialize storage.
     *
     * @param {String} $storageKey
     *                             Key of cache disk from config.
     *
     * @return {void}
     * @member Telenok.Core.Field.Upload.File
     */
    public function diskCache($storageKey = '')
    {
        if ($storageKey) {
            $this->diskCache = app('filesystem')->disk($storageKey);
        }

        return $this->diskCache;
    }

    /**
     * @method path
     * Calculate path to file by its name.
     *
     * @param {String} $filename
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function path($filename = '')
    {
        return implode('/', [
            trim(config('filesystems.upload.protected'), '\\/'),
            substr($filename ?: $this->filename(), 0, 2),
            substr($filename ?: $this->filename(), 2, 2),
            $filename ?: $this->filename(),
        ]);
    }

    /**
     * @method path
     * Calculate path to file by its name.
     *
     * @param {Integer} $width
     *                          Width of image. Can be 0.
     * @param {Integer} $height
     *                          Height of image. Can be 0.
     * @param {String}  $action
     *                          One of constant's like TODO_RESIZE and TODO_RESIZE_PROPORTION from {Telenok.Core.Support.Image.Processing}.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function pathCache($width = 0, $height = 0, $action = '')
    {
        return \App\Vendor\Telenok\Core\Support\File\StoreCache::pathCache($this->filename(), $width, $height, $action);
    }

    /**
     * @method createCache
     * Create file in cache storage.
     *
     * @param {Integer} $width
     *                          Width of image. Can be 0.
     * @param {Integer} $height
     *                          Height of image. Can be 0.
     * @param {String}  $action
     *                          One of constant's like TODO_RESIZE and TODO_RESIZE_PROPORTION from {Telenok.Core.Support.Image.Processing}.
     *
     * @return {void}
     * @member Telenok.Core.Field.Upload.File
     */
    public function createCache($width = 0, $height = 0, $action = '')
    {
        $job = new \App\Vendor\Telenok\Core\Jobs\Cache\Store([
            'path'              => $this->path(),
            'path_cache'        => $this->pathCache($width, $height, $action),
            'storage_key'       => $this->getStorageKey(),
            'storage_cache_key' => $this->getStorageCacheKey(),
            'width'             => $width,
            'height'            => $height,
            'action'            => $action,
        ]);

        if (config('image.cache.queue')) {
            $job->onQueue(\App\Vendor\Telenok\Core\Jobs\Cache\Store::QUEUES_CACHE);

            $this->dispatch($job);
        } else {
            $job->handle();
        }
    }

    /**
     * @method originalFileName
     * Return original name of stored file in model.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function originalFileName()
    {
        return $this->model->{$this->field->code.'_original_file_name'};
    }

    /**
     * @method filename
     * Return name of stored file in model.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function filename()
    {
        return $this->model->{$this->field->code.'_file_name'};
    }

    /**
     * @method filenameCache
     * Return name of cached file from model.
     *
     * @param {Integer} $width
     *                          Width of image. Can be 0.
     * @param {Integer} $height
     *                          Height of image. Can be 0.
     * @param {String}  $action
     *                          One of constant's like TODO_RESIZE and TODO_RESIZE_PROPORTION from {Telenok.Core.Support.Image.Processing}.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function filenameCache($width = 0, $height = 0, $action = '')
    {
        return \App\Vendor\Telenok\Core\Support\File\StoreCache::filenameCache($this->filename(), $width, $height, $action);
    }

    /**
     * @method dir
     * Return name of file's directory.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function dir()
    {
        return pathinfo($this->path(), PATHINFO_DIRNAME);
    }

    /**
     * @method name
     * Alias for method filename(). Return name of stored file in model.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function name()
    {
        return $this->filename();
    }

    /**
     * @method extension
     * Return extnsion of stored file in model.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function extension()
    {
        return pathinfo($this->filename(), PATHINFO_EXTENSION);
    }

    /**
     * @method mimeType
     * Return mime type of stored file in model.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function mimeType()
    {
        if ($this->filename() && $this->model->{$this->field->code.'_file_mime_type'}) {
            return $this->model->{$this->field->code.'_file_mime_type'}->mime_type;
        }
    }

    /**
     * @method size
     * Return size in bytes of stored file in model.
     *
     * @return {Integer}
     * @member Telenok.Core.Field.Upload.File
     */
    public function size()
    {
        if ($this->exists()) {
            return (int) $this->model->{$this->field->code.'_size'};
        }
    }

    /**
     * @method sizeCache
     * Return size in bytes of cached file.
     *
     * @param {Integer} $width
     *                          Width of image. Can be 0.
     * @param {Integer} $height
     *                          Height of image. Can be 0.
     * @param {String}  $action
     *                          One of constant's like TODO_RESIZE and TODO_RESIZE_PROPORTION from {Telenok.Core.Support.Image.Processing}.
     *
     * @return {Integer}
     * @member Telenok.Core.Field.Upload.File
     */
    public function sizeCache($width = 0, $height = 0, $action = '')
    {
        $filename = $this->filenameCache($width, $height, $action);

        if ($this->existsCache($width, $height, $action)) {
            return $this->diskCache()->size($filename);
        }
    }

    /**
     * @method exists
     *
     * @param {String} $path
     *                       Return TRUE if file exists in current storage.
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Upload.File
     */
    public function exists($path = '')
    {
        try {
            return ((!$path && $this->filename()) && ($p = $this->path())) ? $this->disk()->exists($path ?: $p) : false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * @method existsCache
     * Return TRUE if file exists in current cache storage.
     *
     * @param {Integer} $width
     *                          Width of image. Can be 0.
     * @param {Integer} $height
     *                          Height of image. Can be 0.
     * @param {String}  $action
     *                          One of constant's like TODO_RESIZE and TODO_RESIZE_PROPORTION from {Telenok.Core.Support.Image.Processing}.
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Upload.File
     */
    public function existsCache($width = 0, $height = 0, $action = '')
    {
        $filename = $this->filenameCache($width, $height, $action);

        return \App\Vendor\Telenok\Core\Support\File\StoreCache::existsCache($this->getStorageCacheKey(), $filename);
    }

    /**
     * @method isImage
     * Return TRUE if file is image. Validate extension.
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Upload.File
     */
    public function isImage()
    {
        return in_array($this->extension(), \App\Vendor\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION, true);
    }

    /**
     * @method removeCachedFile
     * Delete cached file by path. Path is optional.
     *
     * @param {String} $path
     *
     * @return {Telenok.Core.Field.Upload.File}
     * @member Telenok.Core.Field.Upload.File
     */
    public function removeCachedFile($path = '')
    {
        \App\Vendor\Telenok\Core\Support\File\StoreCache::removeFile($path ?: $this->pathCache());

        return $this;
    }

    /**
     * @method removeFile
     * Delete file by path. Path is optional. Also remove all cached files linked to current model.
     *
     * @param {String} $path
     *
     * @return {Telenok.Core.Field.Upload.File}
     * @member Telenok.Core.Field.Upload.File
     */
    public function removeFile($path = '')
    {
        \App\Vendor\Telenok\Core\Support\File\Store::removeFile($path ?: $this->path());

        $this->removeCachedFile($path);

        return $this;
    }

    /**
     * @method upload
     * Upload file to storage.
     *
     * @param {Telenok.Core.Field.Upload.UploadedFile} $path
     *
     * @return {Telenok.Core.Field.Upload.File}
     * @member Telenok.Core.Field.Upload.File
     */
    public function upload(\Telenok\Core\Field\Upload\UploadedFile $file)
    {
        \App\Vendor\Telenok\Core\Support\File\Store::storeFile($file->getPathname(), $this->path(), json_decode($this->field->upload_storage, true));

        return $this;
    }

    /**
     * @method getStorageKey
     * Return storage's key.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function getStorageKey()
    {
        return $this->storageKey;
    }

    /**
     * @method getStorageCacheKey
     * Return cache storage's key.
     *
     * @return {String}
     * @member Telenok.Core.Field.Upload.File
     */
    public function getStorageCacheKey()
    {
        return $this->storageCacheKey;
    }
}
