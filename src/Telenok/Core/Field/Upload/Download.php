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

			if($range != null)
			{
				$eqPos = strpos($range, "=");
				$toPos = strpos($range, "-");
				$unit = substr($range, 0, $eqPos);
				$start = intval(substr($range, $eqPos + 1, $toPos));
				$success = fseek($stream, $start);

				if($success == 0) 
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

		$fileData = $model->{$field->code};
		
        $responses = \Event::fire('download.file', ['model' => $model, 'field' => $field]);
        $request = $this->getRequest();

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
				if ($secureKey == $fileData->secureKey($width, $height))
				{
					$fileName = $fileData->name();
					$fileExtension = $fileData->extension();
					$dir = rtrim($fileData->dir(), '/\\');

					// create new image with new size and new file name aka 100x220_mypic.jpg
					
					$filePath = $dir . '/' . $fileName . '_' . $width . 'x' . $height . '_' . ($fileExtension ? '.' . $fileExtension : '');
										
					if (!$fileData->exists($filePath))
					{
						$imageContent = $fileData->content();
						
						$imageProcess = app('\App\Telenok\Core\Field\Upload\Image');
						$image = $imageProcess->imagine()->load($imageContent);
						
						$newImageContent = $this->process($image, $width, $height, $toDo)->get($fileExtension, config('image.options', 'gd'));
						
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
				$filePath = $fileData->path();
			}
            
            if ($modifySince = $request->header('If-Modified-Since')) 
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
            
			$fs = $fileData->disk()->getDriver();
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

	public function process($image, $width, $height, $toDo)
	{
		switch ($toDo)
		{
			case File::TODO_RESIZE_PROPORTION:
    				return $this->resizeProportion($image, $width, $height);
                break;

			case File::TODO_RESIZE:
			default:
				return $this->resize($image, $width, $height);

		}
	}

	public function resizeProportion($image, $width, $height)
	{
		$size = $image->getSize();

		if ($width == 0)
		{
			$width = $size->getWidth() * ($height/$size->getHeight());
		}
		else if ($height == 0)
		{
			$height = $size->getHeight() * ($width/$size->getWidth());
		}
		
		return $image->thumbnail(new \Imagine\Image\Box($width, $height));
	}

	public function resize($image, $width, $height)
	{
		$size = $image->getSize();

		if ($width == 0)
		{
			$width = $size->getWidth() * ($height/$size->getHeight());
		}
		else if ($height == 0)
		{
			$height = $size->getHeight() * ($width/$size->getWidth());
		}
		
		$image->resize(new \Imagine\Image\Box($width, $height));
		
		return $image;
	}
}