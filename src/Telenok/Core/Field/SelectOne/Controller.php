<?php

namespace Telenok\Core\Field\SelectOne;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.SelectOne.Controller
 * Class of field "select-one". Field allow to process html select or checkboxes.
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
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    protected $key = 'select-one';

    /**
     * @protected
     *
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    protected $specialField = ['select_one_data'];

    /**
     * @protected
     *
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    protected $allowMultilanguage = true;

    /**
     * @protected
     *
     * @property {String} $viewModel
     * View to show field form's element when creating or updating object
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    protected $viewModel = 'core::field.select-one.model-select-box';

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
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($value === null) {
            $value = array_get((array) json_decode($field->select_one_data, true), 'default', null);
        }

        return $value;
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
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value === null) {
            $value = array_get((array) json_decode($field->select_one_data, true), 'default', null);
        }

        $model->setAttribute($key, $value);
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
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try {
            if (in_array($key, ['select_one_data'], true)) {
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
     * @return {Telenok.Core.Field.SelectOne.Controller}
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['select_one_data'], true)) {
            if ($key == 'select_one_data') {
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

                $value['default'] = array_get($value, 'default', 0);
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
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        $value = $item->{$field->code};

        if (!empty($value)) {
            $config = $field->select_one_data->toArray();

            if ($field->multilanguage) {
                $locale = config('app.locale');
                $title = array_get($config, 'title.'.$locale, []);
                $key = array_get($config, 'key', []);

                $val = array_get(array_combine($key, $title), $value);
            } else {
                $val = array_get($value, 'title');
            }

            return e(\Str::limit($val, 20));
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
     * @member Telenok.Core.Field.SelectOne.Controller
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
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if ($value !== null) {
            $query->whereIn($model->getTable().'.'.$name, $value);
        }
    }

    /**
     * @method getMultilanguageField
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function getMultilanguageField($model, $field)
    {
        return [];
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
     * @return {Telenok.Core.Field.SelectOne.Controller}
     * @member Telenok.Core.Field.SelectOne.Controller
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
     * @return {Telenok.Core.Field.SelectOne.Controller}
     * @member Telenok.Core.Field.SelectOne.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

        if (!\Schema::hasColumn($table, $fieldName)) {
            \Schema::table($table, function (Blueprint $table) use ($fieldName) {
                $table->string($fieldName, 20)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}
