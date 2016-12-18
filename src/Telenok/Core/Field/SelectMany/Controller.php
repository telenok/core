<?php

namespace Telenok\Core\Field\SelectMany;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.SelectMany.Controller
 * Class of field "select-many". Field allow to process html select or checkboxes.
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
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    protected $key = 'select-many';

    /**
     * @protected
     *
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    protected $specialField = ['select_many_data'];

    /**
     * @protected
     *
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    protected $allowMultilanguage = true;

    /**
     * @protected
     *
     * @property {String} $viewModel
     * View to show field form's element when creating or updating object
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    protected $viewModel = 'core::field.select-many.model-select-box';

    /**
     * @method getTranslatedField
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function getTranslatedField($model, $field)
    {
    }

    /**
     * @method saveModelField
     * Save eloquent model with field's data.
     *
     * @param {Telenok.Core.Model.Object.Field}                $field
     *                                                                Eloquent object Field.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     *                                                                Eloquent object.
     * @param {Illuminate.Support.Collection}                  $input
     *                                                                Values of request.
     *
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function saveModelField($field, $model, $input)
    {
        app('db')->table('pivot_relation_o2m_field_select_many')
                ->where('field_id', $field->id)
                ->where('sequence_id', $model->id)
                ->delete();

        foreach ($input->get($field->code) as $key) {
            app('db')->table('pivot_relation_o2m_field_select_many')->insert(
                [
                    'field_id'    => $field->id,
                    'sequence_id' => $model->id,
                    'key'         => $key,
                ]
            );
        }

        return parent::saveModelField($field, $model, $input);
    }

    /**
     * @method getModelAttribute
     * Return processed value of field.
     *
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     *                                                                Eloquent object.
     * @param {String}                                         $key
     *                                                                Field's name.
     * @param {mixed}                                          $value
     *                                                                Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field}                $field
     *                                                                Object with data of field's configuration.
     *
     * @return {String}
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        $value = $value === null ? '[]' : $value;

        $v = json_decode($value, true);

        if (is_array($v)) {
            return collect($v);
        } else {
            return $v;
        }
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
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value instanceof \Illuminate\Support\Collection) {
            $value_ = $value->toArray();
        } elseif (is_array($value)) {
            $value_ = [];

            foreach ($value as $k => $v) {
                $value_[] = $v;
            }
        } else {
            $value_ = (array) $value;
        }

        $model->setAttribute($key, is_null($value_) ? null : json_encode($value_, JSON_UNESCAPED_UNICODE));
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
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try {
            if (in_array($key, ['select_many_data'], true)) {
                return collect(json_decode($value, true));
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
     * @return {Telenok.Core.Field.SelectMany.Controller}
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['select_many_data'], true)) {
            if ($key == 'select_many_data') {
                if ($model->multilanguage) {
                    $default = [];

                    if ($value instanceof \Illuminate\Support\Collection) {
                        if ($value->count()) {
                            $value = $value->toArray();
                        } else {
                            $value = $default;
                        }
                    } else {
                        $value = $value ?: $default;
                    }

                    if (is_array(array_first(array_get($value, 'title')))) {
                        $localeDefault = config('app.localeDefault');

                        $title = array_get($value, 'title.'.$localeDefault, []);

                        foreach (array_get($value, 'title', []) as $k => $t) {
                            if ($k != $localeDefault) {
                                foreach ($t as $k_ => $t_) {
                                    if (!trim($t_)) {
                                        $value['title'][$k][$k_] = $title[$k_];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $value['title'] = array_get($value, 'title', []);
                }

                $defaultKey = [];

                foreach (array_get($value, 'default', []) as $v) {
                    if (strlen(trim($v))) {
                        $defaultKey[] = $v;
                    }
                }

                $value['default'] = $defaultKey;
            }

            $model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
        } else {
            parent::setModelSpecialAttribute($model, $key, $value);
        }

        return $this;
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
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        $value = $item->{$field->code}->toArray();

        if (!empty($value)) {
            $config = $field->select_many_data->toArray();

            if ($field->multilanguage) {
                $locale = config('app.locale');
                $title = array_get($config, 'title.'.$locale, []);
                $key = array_get($config, 'key', []);

                $val = array_only(array_slice(array_combine($key, $title), 0, 10, true), $value);
            } else {
                $val = array_get($value, 'title');
            }

            return e(\Str::limit(implode(', ', $val), 30));
        }
    }

    /**
     * @method getFilterContent
     * Return HTML of filter field in search form.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     *                                                 Object with data of field's configuration.
     *
     * @return {String}
     * @member Telenok.Core.Field.SelectMany.Controller
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
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if ($value !== null) {
            $query->join('pivot_relation_o2m_field_select_many AS p_fsm', function ($join) use ($model, $field, $value) {
                $join->on($model->getTable().'.id', '=', 'p_fsm.sequence_id');
                $join->where('p_fsm.field_id', '=', $field->id);
            });

            $query->whereIn('p_fsm.key', (array) $value);
        }
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
     * @return {Telenok.Core.Field.SelectMany.Controller}
     * @member Telenok.Core.Field.SelectMany.Controller
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
     * @return {Telenok.Core.Field.SelectMany.Controller}
     * @member Telenok.Core.Field.SelectMany.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

        if (!\Schema::hasColumn($table, $fieldName)) {
            \Schema::table($table, function (Blueprint $table) use ($fieldName) {
                $table->string($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}
