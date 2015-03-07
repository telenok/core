<?php

namespace Telenok\Core\Model\Object;

class Tab extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:object_tab,code,:id:,id,tab_object_type,:tab_object_type:', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
	protected $table = 'object_tab';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower((string) $value));
	}

	public function tabObjectType()
	{
		return $this->belongsTo('\App\Model\Telenok\Object\Type', 'tab_object_type');
	}

	public function field()
	{
		return $this->hasMany('\App\Model\Telenok\Object\Field', 'field_object_tab');
	}

    public function preProcess($type, $input)
    {
        $id = $input->get('tab_object_type');
 
        if ($id)
        {
            $tabType = \App\Model\Telenok\Object\Type::where('id', $id)->orWhere('code', $id)->first();

            if ($tabType)
            {
                $input->put('tab_object_type', $tabType->getKey()); 
            }
        }

        return parent::preProcess($type, $input);
    }

}

