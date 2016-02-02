<?php namespace Telenok\Core\Interfaces\Field\Relation;

/**
 * @class Telenok.Core.Interfaces.Field.Relation.Controller
 * Base class for fields which represent relations between models.
 * 
 * @extends Telenok.Core.Interfaces.Field.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    /**
     * @protected
     * @static
     * @property {String} $macroFile
     * Relative path to file where storing relations.
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    protected static $macroFile = 'Model/macro.php';

    /**
     * @static
     * @method readMacroFile
     * Create and include macro file.
     *
     * @return {void}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public static function readMacroFile()
    {
        $path = app_path(static::$macroFile);

        if (!file_exists($path))
        {
            file_put_contents($path, '<?php ' . PHP_EOL . PHP_EOL, LOCK_EX);
        }

        require $path;
    }

    /**
     * @method getLinkedField
     * Define name of special field.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getLinkedField($field)
    {
        
    }

    /**
     * @method getChooseTypeId
     * Return ID of linked Type Object.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Integer}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getChooseTypeId($field)
    {
        return $field->{$this->getLinkedField($field)};
    }

    /**
     * @method getModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        return $value;
    }

    /**
     * @method validateExistsInputField
     * Whether one of values from $param exists in $input.
     *
     * @param {Illuminate.Support.Collection} $input
     * @param {Array} $param
     * @return {void}
     * @throws \Exception
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     * 
     *      @example
     *      $this->validateExistsInputField($input, ['field_has', 'morph_one_to_many_has']);
     */
    public function validateExistsInputField($input, $param = [])
    {
        foreach ((array) $param as $p)
        {
            if ($input->get($p))
            {
                return;
            }
        }

        throw new \Exception('Please, define one or more keys "' . implode('", "', (array) $param)
                        . '" for object_field "' . $input->get('code') . '"'
                        . ' and object_type "' . $input->get('field_object_type')
                        . '"');
    }

    /**
     * @method getListButton
     * Return collection with buttons which showed in tables for linked data.
     *
     * @param {mixed} $item
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {String} $uniqueId
     * Unique string received from ajax.
     * @param {Boolean} $canUpdate
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getListButton($item, $field = null, $type = null, $uniqueId = null, $canUpdate = null)
    {
        $random = str_random();

        $collection = collect();

        $collection->put('open', ['order' => 0, 'content' =>
            '<div class="dropdown">
                <a class="btn btn-white no-hover btn-transparent btn-xs dropdown-toggle" href="#" role="button" style="border:none;"
                        type="button" id="' . $random . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-menu-hamburger text-muted"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="' . $random . '">
            ']);

        $collection->put('close', ['order' => PHP_INT_MAX, 'content' =>
            '</ul>
            </div>']);

        $collection->put('edit', ['order' => 1000, 'content' =>
            '<li><a href="#" onclick="editTableRow' . $field->code . $uniqueId . '(this, \''
            . route($this->getRouteWizardEdit(), ['id' => $item->getKey(), 'saveBtn' => 1, 'chooseBtn' => 0]) . '\'); return false;">'
            . ' <i class="fa fa-pencil"></i> ' . $this->LL('list.btn.edit') . '</a>
                </li>']);

        $collection->put('delete', ['order' => 2000, 'content' =>
            '<li><a href="#" onclick="deleteTableRow' . $field->code . $uniqueId . '(this); return false;">'
            . ' <i class="fa fa-trash-o"></i> ' . $this->LL('list.btn.delete') . '</a>
                </li>']);

        app('events')->fire($this->getListButtonEventKey(), $collection);

        return $this->getAdditionalListButton($item, $collection)->sort(function($a, $b)
                {
                    return array_get($a, 'order', 0) > array_get($b, 'order', 0) ? 1 : -1;
                })->implode('content');
    }

    /**
     * @method getListButtonEventKey
     * Return key for event hook allowed add new buttons.
     *
     * @param {Illuminate.Support.Collection} $param
     * Collection with buttons.
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getListButtonEventKey($param = null)
    {
        return 'telenok.field.' . $this->getKey();
    }

    /**
     * @method getAdditionalListButton
     * Additional buttons.
     *
     * @param {mixed} $item
     * @param {Illuminate.Support.Collection} $collection
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getAdditionalListButton($item, $collection)
    {
        return $collection;
    }

    /**
     * @method getListFieldContent
     * Return value of field for show in list cell like Javascript Datatables().
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Object} $item
     * Eloquent object with data of list's row.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Type of eloquent object $item.
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        $items = [];
        $rows = collect($this->getListFieldContentItems($field, $item, $type));

        if ($rows->count())
        {
            foreach ($rows->slice(0, 7, TRUE) as $row)
            {
                $items[] = \Str::limit($row->translate('title'), 20);
            }

            return e('"' . implode('", "', $items) . '"' . (count($rows) > 7 ? ', ...' : ''));
        }
    }

    /**
     * @method getListFieldContentItems
     * Return initial list of linked field values.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {mixed} $item
     * @param {Telenok.Core.Model.Object.Type} $type
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function getListFieldContentItems($field, $item, $type = null)
    {
        return $item->{camel_case($field->code)}()->take(8)->get();
    }

    /**
     * @method schemeCreateExtraField
     * Alter field's table.
     *
     * @param {String} $table
     * @param {mixed} $p1
     * @param {mixed} $p2
     * @param {mixed} $p3
     * @param {mixed} $p4
     * @param {mixed} $p5
     * @return {void}
     * @member Telenok.Core.Interfaces.Field.Relation.Controller
     */
    public function schemeCreateExtraField($table, $p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null)
    {
    }
}