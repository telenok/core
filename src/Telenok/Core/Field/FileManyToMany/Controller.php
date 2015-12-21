<?php namespace Telenok\Core\Field\FileManyToMany;

class Controller extends \Telenok\Core\Field\RelationManyToMany\Controller {

    protected $key = 'file-many-to-many'; 
    protected $specialField = ['file_many_to_many_allow_ext', 'file_many_to_many_allow_mime'];

    protected $viewModel = "core::field.file-many-to-many.model";
    protected $viewField = "core::field.file-many-to-many.field";

    protected $routeListTable = "telenok.field.relation-many-to-many.list.table";
    protected $routeUpload = 'telenok.field.file-many-to-many.upload';

    public function getRouteUpload()
    {
        return $this->routeUpload;
    }

    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        $linkedField = $this->getLinkedField($field);

        return
        [
            'urlListTitle' => route($this->getRouteListTitle()),
            'urlListTable' => route($this->getRouteListTable(), ['id' => (int)$model->getKey(), 'fieldId' => $field->getKey(), 'uniqueId' => $uniqueId]),
            'urlWizardChoose' => route($this->getRouteWizardChoose(), ['id' => $field->{$linkedField}]),
            'urlWizardCreate' => route($this->getRouteWizardCreate(), ['id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]),
            'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
        ];
    }

	public function getTitleList($id = null, $closure = null)
	{
		$term = trim($this->getRequest()->input('term'));
		$return = [];

		$sequence = new \App\Telenok\Core\Model\Object\Sequence();

		$sequenceTable = $sequence->getTable();
		$typeTable = (new \App\Telenok\Core\Model\Object\Type())->getTable();

		$sequence->addMultilanguage('title_type');

		try
		{
			$query = \App\Telenok\Core\Model\Object\Sequence::withPermission()
                    ->select($sequenceTable . '.id', $sequenceTable . '.title', $typeTable . '.title AS title_type')
					->join($typeTable, function($join) use ($sequenceTable, $typeTable)
					{
						$join->on($sequenceTable . '.sequences_object_type', '=', $typeTable . '.id');
					})
					->where(function ($query) use ($sequenceTable, $typeTable, $term)
					{
						$query->where($sequenceTable . '.id', $term);

						$query->orWhere(function ($query) use ($sequenceTable, $term)
						{
							collect(explode(' ', $term))
									->reject(function($i) { return !trim($i); })
									->each(function($i) use ($query, $sequenceTable)
							{
								$query->where($sequenceTable . '.title', 'like', "%{$i}%");
							});
						});

						$query->orWhere(function ($query) use ($typeTable, $term)
						{
							collect(explode(' ', $term))
									->reject(function($i) { return !trim($i); })
									->each(function($i) use ($query, $typeTable)
							{
								$query->where($typeTable . '.title', 'like', "%{$i}%");
							});
						});
					});
			
			if ($closure instanceof \Closure)
			{
				$closure($query);
			}
			
			$query->take(20)->get()->each(function($item) use (&$return)
			{
				$return[] = ['value' => $item->id, 'text' => "[{$item->translate('title_type')}#{$item->id}] " . $item->translate('title')];
			});
		}
        catch (\Exception $e) {}

		return $return;
	}

    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        return parent::getFormModelContent($controller, $model, $field, $uniqueId);
    } 

    public function getListFieldContent($field, $item, $type = null)
    {
        $linkedObject = $item->{camel_case($field->code)}()->first();

        $content = '';

        if ($linkedObject instanceof \Telenok\Core\Model\File\File)
        {
            $item->{camel_case($field->code)}()->orderBy('sort')->get()->take(5)->each(function($item) use (&$content)
                {
                    if ($item->upload->exists())
                    {
                        if ($item->upload->isImage())
                        {
                            $content .= " <img src='" . $item->upload->downloadImageLink(70, 70) . "' title='" . e($item->translate('title')) . "' />";
                        }
                        else
                        {
                            $content .= " <a href='" . $item->upload->downloadStreamLink() . "' 
                                target='_blank' title='" . e($item->translate('title')) . "'>"
                                    . e(\Str::limit($item->translate('title'), 20)) . "</a>";
                        }
                    }
                    else 
                    {
                        $content .= ' ' . e($item->translate('title'));
                    }
                });
        }
        else
        {
            $item->{camel_case($field->code)}()->get()->take(5)->each(function($item) use (&$content)
                {
                    $content .= ' ' . e(\Str::limit($item->translate('title'), 20));
                });
        }

        return $content;
    }

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            if (in_array($key, ['file_many_to_many_allow_ext', 'file_many_to_many_allow_mime'], true))
            {
				if ($key == 'file_many_to_many_allow_ext')
				{
					$value = $value ? : json_encode(\App\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION);
				}
				else if ($key == 'file_many_to_many_allow_mime')
				{
					$value = $value ? : json_encode(\App\Telenok\Core\Support\Image\Processing::IMAGE_MIME_TYPE);
				}

				return collect((array)json_decode($value, true));
            }
            else
            {
                return parent::getModelSpecialAttribute($model, $key, $value);
            }
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['file_many_to_many_allow_ext', 'file_many_to_many_allow_mime'], true))
        {
			if ($value instanceof \Illuminate\Support\Collection) 
			{
				$value = $value->toArray();
			}
			else if ($key == 'file_many_to_many_allow_ext')
			{
				$value = $value ? : \App\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION;
			} 
			else if ($key == 'file_many_to_many_allow_mime')
			{
				$value = $value ? : \App\Telenok\Core\Support\Image\Processing::IMAGE_MIME_TYPE;
			} 

			$model->setAttribute($key, json_encode((array)$value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            parent::setModelSpecialAttribute($model, $key, $value);
        }
        
        return $this;
    }

    public function saveModelField($field, $model, $input)
    {
		// if created field
		if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
		{
			return $model;
		}

		$idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));
        $idsSort = array_unique((array)$input->get("{$field->code}_sort", []));

		if (app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code))
		{
			if ( !empty($idsAdd) || !empty($idsDelete) || !empty($idsSort) )
			{ 
				$method = camel_case($field->code);

				if (in_array('*', $idsDelete, true))
				{
                    $model->{$method}()->detach();
				}
				else if (!empty($idsDelete))
				{
                    $model->{$method}()->detach($idsDelete);
				}

                // attach new ids
                $maxSort = (int)$model->{$method}()->max('sort');

                foreach($idsAdd as $id)
                {
                    try
                    {
                        if (app('auth')->can('update', $id))
                        {
                            $model->{$method}()->attach($id, ['sort' => ++$maxSort]);
                        }
                    }
                    catch (\Exception $e) {}
                }

                //update sort
                foreach($idsSort as $id => $sort)
                {
                    try
                    {
                        $model->{$method}()->updateExistingPivot($id, ['sort' => $sort]);
                    }
                    catch (\Exception $e) {}
                }
			}
		}
	
        return $model;
    }
    
    public function preProcess($model, $type, $input)
    {
        $input->put('relation_many_to_many_has', \App\Telenok\Core\Model\Object\Type::whereCode('file')->pluck('id'));

        return parent::preProcess($model, $type, $input);
    } 

    public function schemeCreateExtraField($table, $p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null)
    {
        $table->integer('sort')->unsigned()->nullable();
    }

    public function upload()
    {
        $input = $this->getRequestCollected();

        if (!$input->get('title'))
        {
            $input->merge(['title' => ['en' => 'Some file']]);
        }

        $input->merge([
            'active' => 1,
        ]);

        $file = app('\App\Telenok\Core\Model\File\File');

        $model = $file->storeOrUpdate($input->all(), true); 

        return $model->id;
    }

    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}