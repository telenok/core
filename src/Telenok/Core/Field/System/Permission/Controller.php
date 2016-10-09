<?php namespace Telenok\Core\Field\System\Permission;

/**
 * @class Telenok.Core.Field.System.Permission.Controller
 * Class of field "permission". Field allow to store data about date
 * and locker.
 *  
 * @extends Telenok.Core.Abstraction.Field.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    protected $key = 'permission';
    
    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    protected $allowMultilanguage = false;
    
    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.System.Permission.Controller
     */    
    protected $specialField = ['permission_default'];

    /**
     * @method getModelFieldViewVariable
     * Return array with URL for variables in $viewModel view.
     * 
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        return [
            'urlListTitle' => route("telenok.field.permission.list.title"),
        ];
    }

    /**
     * @method getModelSpecialAttribute
     * Return processed value of special fields.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @return {mixed}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['permission_default'], true))
        {
            return collect((array) json_decode($value, true));
        }

        return parent::getModelSpecialAttribute($model, $key, $value);
    }

    /**
     * @method setModelSpecialAttribute
     * Set processed value of special fields.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @return {Telenok.Core.Field.System.Permission.Controller}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['permission_default'], true))
        {
            if ($value instanceof \Illuminate\Support\Collection)
            {
                $value = $value->toArray();
            }

            $model->setAttribute($key, json_encode((array) $value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            return parent::setModelSpecialAttribute($model, $key, $value);
        }

        return $this;
    }

    /**
     * @method getFormModelContent
     * Return HTML content of form element for the field
     * 
     * @param {Telenok.Core.Field.FileManyToMany.Controller} $controller
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        $permissions = $model->type()->permissionType()->get();

        if (!$permissions->count())
        {
            $permissions = \App\Vendor\Telenok\Core\Model\Security\Permission::active()->get();
        }

        $this->setViewModel($field, $controller->getModelFieldView($field), $controller->getModelFieldViewKey($field));

        return view($this->getViewModel(), array_merge([
                    'controllerParent' => $controller,
                    'controller' => $this,
                    'model' => $model,
                    'field' => $field,
                    'uniqueId' => $uniqueId,
                    'permissions' => $permissions,
                    'permissionCreate' => app('auth')->can('create', 'object_field.' . $model->getTable() . '.' . $field->code),
                    'permissionUpdate' => app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code),
                ], 
                (array) $this->getModelFieldViewVariable($controller, $model, $field, $uniqueId), 
                (array) $controller->getModelFieldViewVariable($this, $model, $field, $uniqueId)
            ))->render();
    }

    /**
     * @method setModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from php code for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {void}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
    }

    /**
     * @method getModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
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
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        $items = [];
        $rows = collect(\App\Vendor\Telenok\Core\Model\Security\Permission::take(8)->get());

        if ($rows->count())
        {
            foreach ($rows->slice(0, 7, TRUE) as $row)
            {
                $items[] = $row->translate('title');
            }

            return e('"' . implode('", "', $items) . '"' . (count($rows) > 7 ? ', ...' : ''));
        }
    }

    /**
     * @method getFilterContent
     * Return HTML of filter field in search form.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getFilterContent($field = null)
    {
        return view($this->getViewFilter(), [
                    'controller' => $this,
                    'field' => $field,
                    'permissions' => \App\Vendor\Telenok\Core\Model\Security\Permission::active()->get(),
                ])->render();
    }

    /**
     * @method getFilterQuery
     * Add restrictions to search query.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Object} $model
     * Eloquent object.
     * @param {Illuminate.Database.Query.Builder} $query
     * Laravel query builder object.
     * @param {String} $name
     * Name of field to search for.
     * @param {String} $value
     * Value to search for.
     * @return {void}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if ($value !== null)
        {
            $sequence = new \App\Vendor\Telenok\Core\Model\Object\Sequence();
            $spr = new \App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource();
            $type = new \App\Vendor\Telenok\Core\Model\Object\Type();

            foreach ((array) $value as $permissionId => $ids)
            {
                $query->join($sequence->getTable() . ' as sequence_filter_' . $permissionId, function($query) use ($permissionId, $model)
                {
                    $query->on($model->getTable() . '.id', '=', 'sequence_filter_' . $permissionId . '.id');
                })
                ->join($spr->getTable() . ' as spr_filter_' . $permissionId, function($query) use ($permissionId)
                {
                    $query->on('sequence_filter_' . $permissionId . '.id', '=', 'spr_filter_' . $permissionId . '.acl_resource_object_sequence');
                })
                ->join($type->getTable() . ' as type_filter_' . $permissionId, function($query) use ($permissionId)
                {
                    $query->on('sequence_filter_' . $permissionId . '.sequences_object_type', '=', 'type_filter_' . $permissionId . '.id');
                })
                ->active('spr_filter_' . $permissionId)
                ->active('type_filter_' . $permissionId)
                ->whereIn('spr_filter_' . $permissionId . '.acl_subject_object_sequence', (array) $ids)
                ->where('spr_filter_' . $permissionId . '.acl_permission_object_sequence', $permissionId);
            }
        }
    }

    /**
     * @method getTitleList
     * @member Telenok.Core.Abstraction.Field.Controller
     */
    public function getTitleList($id = null, $closure = null)
    {
        $return = [];

        if (!($term = trim($this->getRequest()->input('term'))))
        {
            return $return;
        }

        if ($id) {
            $model = app(\App\Vendor\Telenok\Core\Model\Object\Sequence::getModel($id)->class_model);
        } else {
            $model = app('\App\Vendor\Telenok\Core\Model\Object\Sequence');
        }

        $query = $model::select([$model->getTable() . '.*', 'resource.code as resource_code'])
                    ->withPermission()->with('sequencesObjectType');

        if (in_array('title', $model->getTranslatedField(), true)) {
            $query->join('object_translation', function ($join) use ($model) {
                $join->on($model->getTable() . '.id', '=', 'object_translation.translation_object_model_id')
                    ->where('object_translation.translation_object_field_code', app('db')->raw("'title'"))
                    ->where('object_translation.translation_object_language', app('db')->raw("'" . config('app.locale') . "'"));
            });
        }

        $query->leftJoin('resource', function ($join) use ($model, $term)
        {
            $join->on($model->getTable() . '.id', '=', 'resource.id');
        });

        $query->where(function ($query) use ($term, $model)
        {
            if (trim($term))
            {
                $query->where(app('db')->raw(1), 0);

                collect(explode(' ', $term))
                    ->reject(function ($i)
                    {
                        return !trim($i);
                    })
                    ->each(function ($i) use ($query, $model)
                    {
                        if (in_array('title', $model->getTranslatedField(), true))
                        {
                            $query->orWhere('object_translation.translation_object_string', 'like', "%{$i}%");
                        }
                        else
                        {
                            $query->orWhere($model->getTable() . '.title', 'like', "%{$i}%");
                        }
                    });

                $query->orWhere($model->getTable() . '.id', (int)$term);
                $query->orWhere('resource.code', 'like', "%{$term}%");
            }
        });

        if ($closure instanceof \Closure)
        {
            $closure($query);
        }

        $query->take(20)->groupBy($model->getTable() . '.id')->get()->each(function ($item) use (&$return) {
            $return[] = [
                'class' => 'searched',
                'value' => $item->id,
                'text' => "[{$item->sequencesObjectType->translate('title')} #{$item->id}] "
                    . $item->translate('title') . (($vv = $item->resource_code)? ' ' . $vv :'')
            ];
        });

        return $return;
    }

    /**
     * @method saveModelField
     * Save eloquent model with field's data.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Eloquent object Field.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function saveModelField($field, $model, $input)
    {
        if (app('auth')->can('update', 'object_field.' . $model->getTable() . '.permission'))
        {
            $permissions = \App\Vendor\Telenok\Core\Model\Security\Permission::active()->get();

            $permissionList = (array) $input->get('permission', []);

            $permissionListDefault = $field->permission_default;

            \App\Vendor\Telenok\Core\Security\Acl::resource($model)->unsetPermission();

            foreach ($permissions->all() as $permission)
            {
                $persmissionIds = [];

                if (isset($permissionList[$permission->code]))
                {
                    $persmissionIds = $permissionList[$permission->code];
                }
                else if ($permissionListDefault->get($permission->code))
                {
                    $persmissionIds = $permissionListDefault->get($permission->code);
                }

                foreach ($persmissionIds as $id)
                {
                    \App\Vendor\Telenok\Core\Security\Acl::subject($id)->setPermission($permission->code, $model);
                }
            }
        }

        return $model;
    }
    
    /**
     * @method preProcess
     * Preprocess save {@link Telenok.Core.Model.Object.Field $model}.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object to save.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Object with data of field's configuration.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * @return {Telenok.Core.Field.System.Permission.Controller}
     * @member Telenok.Core.Field.System.Permission.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $input->put('title', ['en' => 'Permission', 'ru' => 'Разрешение']);
        $input->put('title_list', ['en' => 'Permissions', 'ru' => 'Разрешения']);
        $input->put('code', 'permission');
        $input->put('active', 1);
        $input->put('multilanguage', 0);
        $input->put('field_order', $input->get('field_order', 4));
        $input->put('allow_search', $input->get('allow_search', 1));

        if (!$input->get('field_object_tab'))
        {
            $input->put('field_object_tab', 'additionally');
        }

        $tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab'));

        $input->put('field_object_tab', $tab->getKey());

        return parent::preProcess($model, $type, $input);
    }
}