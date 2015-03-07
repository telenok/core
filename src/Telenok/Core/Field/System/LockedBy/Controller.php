<?php

namespace Telenok\Core\Field\System\LockedBy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

	protected $key = 'locked-by';
    protected $routeListTitle = "cmf.field.relation-one-to-many.list.title";

    public function getDateField($model, $field)
    { 
		return ['locked_at'];
    } 
    public function getModelField($model, $field)
    { 
		return $field->relation_one_to_many_belong_to ? [$field->code, 'locked_at'] : [];
    } 

	public function getModelAttribute($model, $key, $value, $field)
	{ 
		if ($key == 'locked_at' && $value === null)
		{
			$value = \Carbon\Carbon::now();
		}
		
		return $value;
	} 

	public function preProcess($model, $type, $input)
	{
		$translationSeed = $this->translationSeed();
		
 		$input->put('title', array_get($translationSeed, 'model.locked_by'));
		$input->put('title_list', array_get($translationSeed, 'model.locked_by'));
		$input->put('code', 'locked_by_user');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_create', 0);
		$input->put('allow_update', 1); 
		$input->put('relation_one_to_many_belong_to', \DB::table('object_type')->where('code', 'user')->pluck('id'));
		$input->put('field_order', 14);

		if (!$input->get('field_object_tab'))
		{
			$input->put('field_object_tab', 'additionally');
		}
		
		$tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'additionally'));

		$input->put('field_object_tab', $tab->getKey());  

		$table = \App\Model\Telenok\Object\Type::find($input->get('field_object_type'))->code;

		$fieldName = 'locked_by_user';

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->unsigned()->nullable();
			});
		}
		
		$fieldName = 'locked_at';

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName)->nullable();
			});
		}
		
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);
		
		return $this;
	}

    public function postProcess($model, $type, $input)
	{
		return $this;
	}

	public function translationSeed()
	{
		return [
			'model' => [
				'locked_by' => ['en' => 'Locked by', 'ru' => 'Занято'],
			],
		];
	}

}

