<?php namespace Telenok\Core\Model\Object;

class Type extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:object_type,code,:id:,id', 'regex:/^[a-z][\w]*$/i'], 'title_list' => ['required', 'min:1']];
	protected $table = 'object_type';

	public static function boot()
	{
		parent::boot();

		static::deleting(function($model)
		{
            $model->deleteTypeResource();
		});
	}

	public function deleteTypeResource()
	{
		$code = 'object_type.' . $this->code;
 
        \App\Telenok\Core\Model\Security\Resource::where('code', $code)->forceDelete();
	}

	protected function translateSync()
	{
        parent::translateSync();
        
        \App\Telenok\Core\Model\Object\Sequence::where('sequences_object_type', $this->getKey())->update(['treeable' => $this->treeable]);
	}
    
	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = strtolower((string) $value);
	}

	public function field()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Field', 'field_object_type');
	}

	public function tab()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Tab', 'tab_object_type');
	}

	public function sequences()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Sequence', 'sequences_object_type');
	}

}

