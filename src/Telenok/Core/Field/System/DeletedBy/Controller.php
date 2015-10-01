<?php namespace Telenok\Core\Field\System\DeletedBy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

	protected $key = 'deleted-by';
    protected $routeListTitle = "telenok.field.relation-one-to-many.list.title";

	public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
	{
	}

    public function getDateField($model, $field)
    { 
		return ['deleted_at'];
    }

    public function getModelFillableField($model, $field)
    { 
		return $field->relation_one_to_many_belong_to ? [$field->code, 'deleted_at'] : [];
    } 

    public function setModelAttribute($model, $key, $value, $field)
    { 
		if ($key == 'deleted_by_user' && $value === null && $model->deleted_at !== null)
		{
			$value = app('auth')->check() ? app('auth')->user()->id : 0; 
		} 

		$model->setAttribute($key, $value);
    }

    public function getFilterContentOption($field = null)
    {
        return ["<option value='*'>" . $this->LL('title.value.any') . "</option>"];
    }
    
	public function getTitleList($id = null, $closure = null)
    {
        $list = ['value' => "*", 'text' => $this->LL('title.value.any')];
     
        if ($list_ = parent::getTitleList($id, $closure))
        {
            $list = $list + $list_;
        }

        return $list;
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (empty($value))
        {
            $query->whereNull($model->getTable() . '.deleted_at');
        }
        else
        {
            $query->whereNotNull($model->getTable() . '.deleted_at');

            if (!in_array("*", $value))
            {
                parent::getFilterQuery($field, $model, $query, $name, $value);
            }
        }
    }

	public function preProcess($model, $type, $input)
	{
		$translationSeed = $this->translationSeed();
		
 		$input->put('title', array_get($translationSeed, 'model.deleted_by'));
		$input->put('title_list', array_get($translationSeed, 'model.deleted_by'));
		$input->put('code', 'deleted_by_user');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('allow_create', 0);
		$input->put('allow_update', 0); 
		$input->put('relation_one_to_many_belong_to', \DB::table('object_type')->where('code', 'user')->pluck('id'));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);
        
		if (!$input->get('field_object_tab'))
		{
			$input->put('field_object_tab', 'additionally');
		}
		
		$tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'additionally'));

		$input->put('field_object_tab', $tab->getKey());  

		$table = \App\Telenok\Core\Model\Object\Type::find($input->get('field_object_type'))->code;

		$fieldName = 'deleted_by_user';

		if (!\Schema::hasColumn($table, $fieldName))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->unsigned()->nullable();
			});
		}
		
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
				'deleted_by' => ['en' => 'Deleted by', 'ru' => 'Создано'],
			],
		];
	}

}

