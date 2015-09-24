<?php

namespace Telenok\Core\Field\ComplexArray;

use Illuminate\Database\Schema\Blueprint;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'complex-array';
    protected $allowMultilanguage = false;

    public function getListFieldContent($field, $item, $type = null)
    {
        if ($item instanceof \Illuminate\Support\Collection)
        {
            return 'Complex array';
        } 
        else
        {
            return \Str::limit($item->{$field->code}, 20);
        }
    }

    public function getModelAttribute($model, $key, $value, $field)
    {
        $value = $value === null || $value === "" ? '[]' : $value;

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

    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value instanceof \Illuminate\Support\Collection)
        {
            $value_ = $value->toArray();
        } 
        else
        {
            $value_ = $value === null ? [] : $value;
        }

        $model->setAttribute($key, json_encode($value_, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
    }

    public function preProcess($model, $type, $input)
    {
        $input->put('multilanguage', 0);
        $input->put('allow_sort', 0);

        return parent::preProcess($model, $type, $input);
    }

    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->getAttribute('code');
        $fieldName = $model->getAttribute('code');

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->longText($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}