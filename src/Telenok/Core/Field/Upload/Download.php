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
			$fileObject = $model->{$field->code};
            
			$fs = $fileObject->diskCache()->getDriver();
			$stream = $fs->readStream($fileObject->pathCache());
			$fullsize = $fileObject->size();
			$size = $fullsize;
			$response_code = 200;
			$headers = ["Content-type" => $fileObject->mimeType()];

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

	public function image($modelId, $fieldId, $toDo, $width, $height)
	{
		$model = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);

		$fileObject = $model->{$field->code};

        $responses = \Event::fire('download.file', ['model' => $model, 'field' => $field]); 

        if (in_array(false, $responses, true))
        {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
        }

		if (!in_array(false, $responses, true) 
            && (($model instanceof \App\Telenok\Core\Model\File\File && app('auth')->can('read', $model))
                || (!($model instanceof \App\Telenok\Core\Model\File\File) && 
                    app('auth')->can('read', 'object_field.' . $model->getTable() . '.' . $field->code))))
		{
            if ($modifySince = $this->getRequest()->header('If-Modified-Since')) 
            {
               $modifiedSince = explode(';', $modifySince);
               $modifiedSince = strtotime($modifiedSince[0]);
            } 
            else 
            {
               $modifiedSince = 0;
            }
            
            if ($model->updated_at->getTimestamp() <= $modifiedSince)
            {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }

			$width = intval($width); 
			$height = intval($height);

			$fs = $fileObject->diskCache()->getDriver();
			$stream = $fs->readStream($pathCache = $fileObject->pathCache(null, $width, $height, $toDo));

			return \Response::stream(function() use($stream) {
								fpassthru($stream);
							}, 200, [
								"Content-Type" => $fileObject->mimeType(),
								"Content-Length" => $fileObject->mimeType(),
								"Etag" => ($m5 = md5($pathCache . $width . $height . $toDo)),
								"Last-Modified" => $model->updated_at->toRfc2822String(),
								"Cache-Control" => 'private, must-revalidate',
								"Expires" => date(\DateTime::RFC822, strtotime("20 day")),
								"Vary" => 'Content-ID',
								"Content-ID" => $m5,
							]);
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}
}