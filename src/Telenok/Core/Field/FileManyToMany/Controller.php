<?php namespace Telenok\Core\Field\FileManyToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Field\RelationManyToMany\Controller {

    protected $key = 'file-many-to-many'; 
    protected $specialField = ['file_many_to_many_allow_ext', 'file_many_to_many_allow_mime'];

    protected $viewModel = "core::field.file-many-to-many.model";
    protected $viewField = "core::field.file-many-to-many.field";
    
    protected $routeListTable = "telenok.field.relation-many-to-many.list.table";
    protected $routeListTitle = "telenok.field.relation-many-to-many.list.title";
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
            'urlListTitle' => route($this->getRouteListTitle(), ['id' => (int)$field->{$linkedField}]),
            'urlListTable' => route($this->getRouteListTable(), ['id' => (int)$model->getKey(), 'fieldId' => $field->getKey(), 'uniqueId' => $uniqueId]),
            'urlWizardChoose' => route($this->getRouteWizardChoose(), ['id' => $field->{$linkedField}]),
            'urlWizardCreate' => route($this->getRouteWizardCreate(), ['id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]),
            'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
        ];
    }
    
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {         
        if ($field->relation_many_to_many_has)
        {
            return parent::getFormModelContent($controller, $model, $field, $uniqueId);
        }
    } 

    public function getListFieldContent($field, $item, $type = null)
    {
        $linkedObject = $item->{camel_case($field->code)}()->first();

        $content = '';
        
        if ($linkedObject instanceof \Telenok\Core\Model\File\File)
        {
            $item->{camel_case($field->code)}()->get()->take(5)->each(function($item) use (&$content)
                {
                    if ($item->upload->exists())
                    {
                        if ($item->upload->isImage())
                        {
                            $content .= " <img src='" . $item->upload->downloadImageLink(140, 140) . "' alt='" . e($item->translate('title')) . "' />";
                        }
                        else
                        {
                            $content .= " <a href='" . $item->upload->downloadStreamLink() . "' target='_blank'>" . e($item->translate('title')) . '</a>';
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
                    $content .= ' ' . e($item->translate('title'));
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
					$value = $value ? : json_encode(\Telenok\Core\Field\Upload\File::IMAGE_EXTENSION);
				}
				else if ($key == 'file_many_to_many_allow_mime')
				{
					$value = $value ? : json_encode(\Telenok\Core\Field\Upload\File::IMAGE_MIME_TYPE);
				}

				return \Illuminate\Support\Collection::make((array)json_decode($value, true));
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
				$value = $value ? : \Telenok\Core\Field\Upload\File::IMAGE_EXTENSION;
			} 
			else if ($key == 'file_many_to_many_allow_mime')
			{
				$value = $value ? : \Telenok\Core\Field\Upload\File::IMAGE_MIME_TYPE;
			} 

			$model->setAttribute($key, json_encode((array)$value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            parent::setModelSpecialAttribute($model, $key, $value);
        }
        
        return $this;
    }

    public function preProcess($model, $type, $input)
    {
        $input->put('relation_many_to_many_has', \App\Telenok\Core\Model\Object\Type::whereCode('file')->pluck('id'));

        if (!$input->get('show_in_form_belong'))
        {
            $input->put('show_in_form_belong', 0);
        } 

        return parent::preProcess($model, $type, $input);
    } 
    
    public function upload()
    {
        $request = $this->getRequest();

        if (!$request->has('title'))
        {
            $request->merge(['title' => ['en' => 'Some file']]);
        }

        $request->merge([
            'active' => 1,
            'category_add' => (array)$request->get('category', [])
        ]);

        $file = app('\App\Telenok\Core\Model\File\File');

        $model = $file->storeOrUpdate($request->all(), true); 

        return $model->id;
    }    
}