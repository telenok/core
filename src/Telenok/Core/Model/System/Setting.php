<?php

namespace Telenok\Core\Model\System;

class Setting extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'setting';
	
	public static function boot()
	{
		parent::boot();

		static::creating(function($model)
		{
			\Event::fire('telenok.compile.setting');
		});

		static::saved(function($model)
		{
			\Event::fire('telenok.compile.setting');
		});

		static::deleting(function($model)
		{
			\Event::fire('telenok.compile.setting');
		});
	}
	
}

