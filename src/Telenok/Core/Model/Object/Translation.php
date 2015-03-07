<?php

namespace Telenok\Core\Model\Object;

class Translation extends \Illuminate\Database\Eloquent\Model {

	public $timestamps = false;
	protected $fillable = ['translation_object_model_id', 'translation_object_field_code', 'translation_object_language', 'translation_object_string'];
	protected $table = 'object_translation';

}

