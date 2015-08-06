<?php namespace Telenok\Core\Field\Upload;

class Download extends \Telenok\Core\Interfaces\Controller\Controller {

	public function stream($modelId, $fieldId)
	{
		$model = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);
		
		if (app('auth')->can('read', 'object_field.' . $model->getTable() . '.' . $field->code))
		{
			$file = $model->{$field->code}->path();

			$fs = $model->{$field->code}->disk()->getDriver();
			$stream = $fs->readStream($file);

			return \Response::stream(function() use($stream) {
				fpassthru($stream);
			}, 200, [
				'Content-Type' => $fs->getMimetype($file),
				"Content-Length: " => $fs->getSize($file),
				"Content-disposition" => "attachment; filename=\"" . basename($model->{$field->code}->originalFileName()) . "\"",
			]);
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}
	
	public function image($modelId, $fieldId, $width, $height, $secureKey)
	{
		$model = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);

		$fileData = $model->{$field->code};

		if ($fileData->isImage() && app('auth')->can('read', 'object_field.' . $model->getTable() . '.' . $field->code))
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
						
						$newImageContent = $this->process($image, $width, $height)->get($fileExtension, config('image.options', 'gd'));
						
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

			$fs = $fileData->disk()->getDriver();
			$stream = $fs->readStream($filePath);

			return \Response::stream(function() use($stream) {
				fpassthru($stream);
			}, 200, [
				'Content-Type' => $fs->getMimetype($filePath),
				"Content-Length: " => $fs->getSize($filePath),
			]);
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}
	
	public function process($image, $width, $height)
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