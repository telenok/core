<?php

namespace Telenok\Core\Model\System;

class Buffer extends \Illuminate\Database\Eloquent\Model {

	protected $table = 'buffer';
	protected $fillable = ['id', 'user_id', 'sequence_id', 'key', 'place']; 

	public function sequence()
	{
		return $this->hasOne('\App\Model\Telenok\Object\Sequence', 'id');
	}
	
	public static function addBuffer($user_id = 0, $sequence_id = 0, $place = 'object', $key = 'cut')
	{ 
		try
		{
			$instance = (new static)->create(
				[
					'user_id' => $user_id,
					'sequence_id' => $sequence_id,
					'key' => $key,
					'place' => $place,
				]);
		}
		catch (\Illuminate\Database\QueryException $exc)
		{
			$instance = (new static)->where(function($query) use ($user_id, $sequence_id, $place)
				{
					$query->where('user_id', $user_id);
					$query->where('sequence_id', $sequence_id);
					$query->where('place', $place);
				})
			->firstOrFail();
				
			$instance->update(
				[
					'user_id' => $user_id,
					'sequence_id' => $sequence_id,
					'key' => $key,
					'place' => $place,
				]);
		}
		
		return $instance;
	}
}

