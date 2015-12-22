<?php namespace Telenok\Core\Field\Upload;

use \App\Telenok\Core\Field\Upload\File;

class Download extends \Telenok\Core\Interfaces\Controller\Controller {

	public function stream($modelId, $fieldId)
	{
		$model = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);
		
        $responses = \Event::fire('download.file', ['model' => $model, 'field' => $field]);

		if (!in_array(false, $responses, true) 
            && (($model instanceof \App\Telenok\Core\Model\File\File && app('auth')->can('read', $model))
                || (!($model instanceof \App\Telenok\Core\Model\File\File) && 
                    app('auth')->can('read', 'object_field.' . $model->getTable() . '.' . $field->code))))
		{
			$fileData = $model->{$field->code};

			$file = $fileData->path();

			$fs = $fileData->disk()->getDriver();
			$stream = $fs->readStream($file);

			$fullsize = $fileData->size();
			$size = $fullsize;
			$response_code = 200;
			$headers = ["Content-type" => $fs->getMimetype($file)];

			// Check for request for part of the stream
			$range = $this->getRequest()->header('Range');

			if ($range != null)
			{
				$eqPos = strpos($range, "=");
				$toPos = strpos($range, "-");
				$unit = substr($range, 0, $eqPos);
				$start = intval(substr($range, $eqPos + 1, $toPos));
				$success = fseek($stream, $start);

				if ($success == 0) 
				{
					$size = $fullsize - $start;
					$response_code = 206;
					$headers["Accept-Ranges"] = $unit;
					$headers["Content-Range"] = $unit . " " . $start . "-" . ($fullsize-1) . "/" . $fullsize;
				}
			}

			header('HTTP/1.0 206 Partial Content');

			$headers["Content-Length"] = $size;
			$headers["Content-Disposition"] = 'inline';
			$headers['Accept-Ranges'] = 'bytes';
			$headers["Content-disposition"] = "attachment; filename=\"" . basename($model->{$field->code}->originalFileName()) . "\"";

			return \Response::stream(function () use ($stream) {
				fpassthru($stream);
			}, $response_code, $headers);
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}

	public function image($modelId, $fieldId, $toDo, $width, $height, $secureKey)
	{
		$model = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);

		$fileObject = $model->{$field->code};

        $responses = \Event::fire('download.file', ['model' => $model, 'field' => $field]); 

        if (in_array(false, $responses, true))
        {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
        }

        // verify if we can store cache file in public directory
        if (\App\Telenok\Core\Security\Acl::subjectAny(['user_any', 'user_unauthorized'])
                ->can('read', [$model, 'object_field.' . $model->getTable() . '.' . $field->code]))
        {
            $cache = new \Telenok\Core\Support\File\Cache();
            $cache->filename($fileObject->filename());
            $cache->disk();

            if (!$cache->exists())
            {
                copy($source, $destination);
            }


            header('redirect to cache file');



            if ($fileObject->diskCache())
            {
                
            }
            
            
            return app('\App\Telenok\Core\Support\Image\Processing')
                ->cachedModelImageUrl($this->model->{$this->field->code}->path(), 300, 300);
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
		if (!in_array(false, $responses, true) 
            && (($model instanceof \App\Telenok\Core\Model\File\File && app('auth')->can('read', $model))
                || (!($model instanceof \App\Telenok\Core\Model\File\File) && 
                    app('auth')->can('read', 'object_field.' . $model->getTable() . '.' . $field->code))))
		{
			$width = intval($width); 
			$height = intval($height);

			// validate $secureKey only if image not exists (to prevent creating by user many images with random size)
			if ($width || $height)
			{
				if ($secureKey == $fileObject->secureKey($width, $height))
				{
					$fileName = $fileObject->name();
					$fileExtension = $fileObject->extension();
					$dir = rtrim($fileObject->dir(), '/\\');

					// create new image with new size and new file name aka 100x220_mypic.jpg
					
					$filePath = $dir . '/' . $fileName . '_' . $width . 'x' . $height . '_' . ($fileExtension ? '.' . $fileExtension : '');
										
					if (!$fileObject->exists($filePath))
					{
						$imageContent = $fileObject->content();
						
						$imageProcess = app('\App\Telenok\Core\Support\Image\Processing');
						$imageProcess->setImage($imageProcess->imagine()->load($imageContent));
						
						$newImageContent = $imageProcess->process($width, $height, $toDo)->get($fileExtension, config('image.options'));
						
						foreach(File::storageList($field->upload_storage)->all() as $storage)
						{
							$disk = app('filesystem')->disk($storage);

							$disk->put($filePath, $newImageContent, \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PRIVATE);
						}
					}
				}
				else
				{
					throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
				}
			}
			else 
			{
				$filePath = $fileObject->path();
			}
            
            if ($modifySince = $this->getRequest()->header('If-Modified-Since')) 
            {
               $modifiedSince = explode(';', $modifySince);
               $modifiedSince = strtotime($modifiedSince[0]);
            } 
            else 
            {
               $modifiedSince = 0;
            }
            
            if ($field->updated_at->getTimestamp() <= $modifiedSince)
            {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }
            
			$fs = $fileObject->disk()->getDriver();
			$stream = $fs->readStream($filePath);
			
			$response =  \Response::stream(function() use($stream) {
								fpassthru($stream);
							}, 200, [
								"Content-Type" => $fs->getMimetype($filePath),
								"Content-Length" => $fs->getSize($filePath),
								"Etag" => ($m5 = md5($filePath . $width . $height)),
								"Last-Modified" => $field->updated_at->toRfc2822String(),
								"Cache-Control" => 'private, must-revalidate',
								"Expires" => date(\DateTime::RFC822, strtotime("20 day")),
								"Vary" => 'Content-ID',
								"Content-ID" => $m5,
							]);

			return $response;
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}
}