<?php

namespace Telenok\Core\Field\TimeRange;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.TimeRange.Controller
 * Class of field "time-range". Field allow to store daytime.
 *
 * @extends Telenok.Core.Abstraction.Field.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Controller
{
    /**
     * @protected
     *
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    protected $key = 'time-range';

    /**
     * @protected
     *
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    protected $allowMultilanguage = false;

    /**
     * @protected
     *
     * @property {Array} $specialDateField
     * Define list of date field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    protected $specialDateField = ['time_range_default_start', 'time_range_default_end'];

    /**
     * @method getDateField
     * Define list of date fields in Eloquent object to process it saving and filling.
     *
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     *                                                                Eloquent object.
     * @param {Telenok.Core.Model.Object.Field}                $field
     *                                                                Object with data of field's configuration.
     *
     * @return {Array}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function getDateField($model, $field)
    {
        return [$field->code.'_start', $field->code.'_end'];
    }

    /**
     * @method getModelFillableField
     * Define list of fields in Eloquent object which can be filled by user.
     *
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     *                                                                Eloquent object.
     * @param {Telenok.Core.Model.Object.Field}                $field
     *                                                                Object with data of field's configuration.
     *
     * @return {Array}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return [];
    }

    /**
     * @method getListFieldContent
     * Return value of field for show in list cell like Javascript Datatables().
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     *                                                 Object with data of field's configuration.
     * @param {Object}                          $item
     *                                                 Eloquent object with data of list's row.
     * @param {Telenok.Core.Model.Object.Type}  $type
     *                                                 Type of eloquent object $item.
     *
     * @return {String}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        $value = [];
        $value[] = ($v = $item->{$field->code.'_start'}) ? $v->toTimeString() : '';
        $value[] = ($v = $item->{$field->code.'_end'}) ? $v->toTimeString() : '';

        return e(count($value) ? implode(' ... ', $value) : '');
    }

    /**
     * @method setModelAttribute
     * Return processed value of field.
     *
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     *                                                                Eloquent object.
     * @param {String}                                         $key
     *                                                                Field's name.
     * @param {mixed}                                          $value
     *                                                                Value of field from php code for processing in this method.
     * @param {Telenok.Core.Model.Object.Field}                $field
     *                                                                Object with data of field's configuration.
     *
     * @return {void}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if (in_array($key, [$field->code.'_start', $field->code.'_end'], true)) {
            if ($value === null) {
                $value = $field->{$key} ?: null;
            } elseif (is_scalar($value) && $value) {
                $value = \Carbon\Carbon::createFromFormat('H:i:s', $value);
            }
        }

        parent::setModelAttribute($model, $key, $value, $field);
    }

    /**
     * @method getModelSpecialAttribute
     * Return processed value of special fields.
     *
     * @param {Telenok.Core.Model.Object.Field} $model
     *                                                 Eloquent object.
     * @param {String}                          $key
     *                                                 Field's name.
     * @param {mixed}                           $value
     *                                                 Value of field from database for processing in this method.
     *
     * @return {mixed}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try {
            if (in_array($key, ['time_range_default_start', 'time_range_default_end'], true) && $value === null) {
                return \Carbon\Carbon::now();
            } else {
                return parent::getModelSpecialAttribute($model, $key, $value);
            }
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * @method setModelSpecialAttribute
     * Set processed value of special fields.
     *
     * @param {Telenok.Core.Model.Object.Field} $model
     *                                                 Eloquent object.
     * @param {String}                          $key
     *                                                 Field's name.
     * @param {mixed}                           $value
     *                                                 Value of field from database for processing in this method.
     *
     * @return {Telenok.Core.Field.TimeRange.Controller}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['time_range_default_start', 'time_range_default_end'], true)) {
            if ($value === null) {
                $value = \Carbon\Carbon::now();
            } elseif (is_scalar($value) && $value) {
                $value = \Carbon\Carbon::createFromFormat('H:i:s', $value);
            }
        }

        return parent::setModelSpecialAttribute($model, $key, $value);
    }

    /**
     * @method getFilterContent
     * Return HTML of filter field in search form.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     *                                                 Object with data of field's configuration.
     *
     * @return {String}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function getFilterContent($field = null)
    {
        return view($this->getViewFilter(), [
                    'controller' => $this,
                    'field'      => $field,
                ])->render();
    }

    /**
     * @method getFilterQuery
     * Add restrictions to search query.
     *
     * @param {Telenok.Core.Model.Object.Field}   $field
     *                                                   Object with data of field's configuration.
     * @param {Object}                            $model
     *                                                   Eloquent object.
     * @param {Illuminate.Database.Query.Builder} $query
     *                                                   Laravel query builder object.
     * @param {String}                            $name
     *                                                   Name of field to search for.
     * @param {String}                            $value
     *                                                   Value to search for.
     *
     * @return {void}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if ($value !== null) {
            $query->where(function ($query) use ($value, $name, $model) {
                if ($v = trim(array_get($value, 'start'))) {
                    $query->where(app('db')->raw('TIME('.$model->getTable().'.'.$name.'_end)'), '>=', $v);
                }

                if ($v = trim(array_get($value, 'end'))) {
                    $query->where(app('db')->raw('TIME('.$model->getTable().'.'.$name.'_start)'), '<=', $v);
                }
            });
        }
    }

    /**
     * @method processFieldDelete
     * Delete special fields from table.
     *
     * @param {Telenok.Core.Model.Object.Field} $model
     *                                                 Object with data of field's configuration.
     * @param {Telenok.Core.Model.Object.Type}  $type
     *                                                 Type of eloquent object $model.
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function processFieldDelete($model, $type)
    {
        \Schema::table($type->code, function ($table) use ($model) {
            $table->dropColumn($model->code.'_start');
            $table->dropColumn($model->code.'_end');
        });

        return true;
    }

    /**
     * @method preProcess
     * Preprocess save {@link Telenok.Core.Model.Object.Field $model}.
     *
     * @param {Telenok.Core.Model.Object.Field} $model
     *                                                 Object to save.
     * @param {Telenok.Core.Model.Object.Type}  $type
     *                                                 Object with data of field's configuration.
     * @param {Illuminate.Http.Request}         $input
     *                                                 Laravel request object.
     *
     * @return {Telenok.Core.Field.TimeRange.Controller}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function preProcess($model, $type, $input)
    {
        if ($input->get('required')) {
            $input->put('rule', ['required']);
        } else {
            $input->put('rule', []);
        }

        return parent::preProcess($model, $type, $input);
    }

    /**
     * @method postProcess
     * postProcess save {@link Telenok.Core.Model.Object.Field $model}.
     *
     * @param {Telenok.Core.Model.Object.Field} $model
     *                                                 Object to save.
     * @param {Telenok.Core.Model.Object.Type}  $type
     *                                                 Object with data of field's configuration.
     * @param {Illuminate.Http.Request}         $input
     *                                                 Laravel request object.
     *
     * @return {Telenok.Core.Field.TimeRange.Controller}
     * @member Telenok.Core.Field.TimeRange.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

        if (!\Schema::hasColumn($table, $fieldName.'_start')) {
            \Schema::table($table, function (Blueprint $table) use ($fieldName) {
                $table->timestamp($fieldName.'_start')->nullable();
            });
        }

        if (!\Schema::hasColumn($table, $fieldName.'_end')) {
            \Schema::table($table, function (Blueprint $table) use ($fieldName) {
                $table->timestamp($fieldName.'_end')->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}
