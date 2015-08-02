<?php

namespace Telenok\Core\Support\File;

class Download extends \Telenok\Core\Interfaces\Controller\Controller {

	public function stream($modelId, $fieldId)
	{
		$object = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);
		
		if (app('auth')->can('read', 'object_field.' . $object->getTable() . '.' . $field->code))
		{
			$file = $object->{$field->code}->path();

			$fs = $object->{$field->code}->disk()->getDriver();
			$stream = $fs->readStream($file);

			return \Response::stream(function() use($stream) {
				fpassthru($stream);
			}, 200, [
				'Content-Type' => $fs->getMimetype($file),
				"Content-Length: " => $fs->getSize($file),
				"Content-disposition" => "attachment; filename=\"" . basename($object->{$field->code}->originalFileName()) . "\"",
			]);
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}
	
	public function image($modelId, $fieldId)
	{
		$object = \App\Telenok\Core\Model\Object\Sequence::getModel($modelId);
		$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);
		
		if (app('auth')->can('read', 'object_field.' . $object->getTable() . '.' . $field->code))
		{
			$file = $object->{$field->code}->path();

			$fs = $object->{$field->code}->disk()->getDriver();
			$stream = $fs->readStream($file);

			return \Response::stream(function() use($stream) {
				fpassthru($stream);
			}, 200, [
				'Content-Type' => $fs->getMimetype($file),
				"Content-Length: " => $fs->getSize($file),
				//"Content-disposition" => "attachment; filename=\"" . basename($object->{$field->code}->originalFileName()) . "\"",
			]);
		}
		else
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
		}
	}
}