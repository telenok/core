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
        if ($item instanceof \Telenok\Core\Model\File\File)
        {
            $file = $item;
        }
        else 
        {
            $file = $item->{camel_case($field->code)}()->first();
        }
        
        if ($file && $file->upload_path)
        {
            if ($file->isImage())
            {
                return "<img src='" . $file->upload->downloadImageLink() . "' alt='' width='140' />";
            }
            else
            {
                return "<a href='" . $file->upload->downloadStreamLink() . "' target='_blank'>" . e($file->translate('title')) . '</a>';
            }
        }
    }

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            if (in_array($key, ['file_many_to_many_allow_ext', 'file_many_to_many_allow_mime'], true))
            {
                $value = $value ? : '[]';

                $v = json_decode($value, true);

                if (is_array($v))
                {
                    return \Illuminate\Support\Collection::make($v);
                }
                else
                {
                    return $v;
                }
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
            $default = [];

            if ($value instanceof \Illuminate\Support\Collection) 
            {
                if ($value->count())
                {
                    $value = $value->toArray();
                }
                else
                {
                    $value = $default;
                }
            }
            else
            {
                $value = $value ? : $default;
            } 

            $model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
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
        if (!$this->getRequest()->has('title'))
        {
            $this->getRequest()->merge(['title' => ['en' => 'Some file']]);
        }

        $this->getRequest()->merge(['active' => 1]);

        $file = app('\App\Telenok\Core\Module\Objects\Lists\Controller');

        $model = $file->save(null, 'file'); 
        
        return $model->id;
    }    
}