<?php namespace Telenok\Core\Field\FileManyToMany;

/**
 * @class Telenok.Core.Field.FileManyToMany.Controller
 * Class of field "file-many-to-many". Field allow to manipulate list of files.
 * 
 * @extends Telenok.Core.Field.RelationManyToMany.Controller
 */
class Controller extends \Telenok\Core\Field\RelationManyToMany\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    protected $key = 'file-many-to-many';
    
    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    protected $specialField = ['file_many_to_many_allow_ext', 'file_many_to_many_allow_mime'];

    /**
     * @protected
     * @property {String} $viewModel
     * View to show field form's element when creating or updating object
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    protected $viewModel = "core::field.file-many-to-many.model";

    /**
     * @protected
     * @property {String} $viewField
     * View to show special field's form-element when creating or updating {Telenok.Core.Model.Object.Field}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    protected $viewField = "core::field.file-many-to-many.field";

    /**
     * @protected
     * @property {String} $routeListTable
     * Router name to return list with json data in $viewModel view
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    protected $routeListTable = "telenok.field.relation-many-to-many.list.table";

    /**
     * @protected
     * @property {String} $routeUpload
     * Router name to upload new file
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    protected $routeUpload = 'telenok.field.file-many-to-many.upload';

    /**
     * @method getRouteUpload
     * Return name of upload router.
     * 
     * @return {String}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    public function getRouteUpload()
    {
        return $this->routeUpload;
    }

    /**
     * @method getModelFieldViewVariable
     * Return array with URL for variables in $viewModel view.
     * 
     * @param {Telenok.Core.Field.FileManyToMany.Controller} $controller
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * 
     * @return {Array}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
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

    /**
     * @method getTitleList
     * Return array with titles of model's records
     * 
     * @param {Integer} $id
     * ID of Telenok.Core.Model.Object.Type 
     * @param {Function} $closure
     * Closure to adding eloquent builder's query filter
     * 
     * @return {Array}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
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
                ->select($sequenceTable . '.id', $sequenceTable . '.title', $typeTable . '.title as title_type')
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
                        ->reject(function($i)
                        {
                            return !trim($i);
                        })
                        ->each(function($i) use ($query, $sequenceTable)
                        {
                            $query->where($sequenceTable . '.title', 'like', "%{$i}%");
                        });
                    });

                    $query->orWhere(function ($query) use ($typeTable, $term)
                    {
                        collect(explode(' ', $term))
                        ->reject(function($i)
                        {
                            return !trim($i);
                        })
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
        catch (\Exception $e)
        {
        }

        return $return;
    }

    /**
     * @method getFormModelContent
     * Return HTML content of form element for the field
     * 
     * @param {Telenok.Core.Field.FileManyToMany.Controller} $controller
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        return parent::getFormModelContent($controller, $model, $field, $uniqueId);
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
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
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
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
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
     * @return {Telenok.Core.Field.FileManyToMany.Controller}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
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

    /**
     * @method saveModelField
     * Save eloquent model with field's data.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Eloquent object Field.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @return {Telenok.Core.Interfaces.Eloquent.Object.Model}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    public function saveModelField($field, $model, $input)
    {
        // if created field
        if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
        {
            return $model;
        }

        $idsAdd = array_unique((array) $input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array) $input->get("{$field->code}_delete", []));
        $idsSort = array_unique((array) $input->get("{$field->code}_sort", []));

        if (app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code))
        {
            if (!empty($idsAdd) || !empty($idsDelete) || !empty($idsSort))
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
                $maxSort = (int) $model->{$method}()->max('sort');

                foreach ($idsAdd as $id)
                {
                    try
                    {
                        if (app('auth')->can('update', $id))
                        {
                            $model->{$method}()->attach($id, ['sort' => ++$maxSort]);
                        }
                    }
                    catch (\Exception $e)
                    {
                        
                    }
                }

                //update sort
                foreach ($idsSort as $id => $sort)
                {
                    try
                    {
                        $model->{$method}()->updateExistingPivot($id, ['sort' => $sort]);
                    }
                    catch (\Exception $e)
                    {
                    }
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
     * @return {Telenok.Core.Field.FileManyToMany.Controller}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $input->put('relation_many_to_many_has', \App\Telenok\Core\Model\Object\Type::whereCode('file')->value('id'));

        return parent::preProcess($model, $type, $input);
    } 

    /**
     * @method schemeCreateExtraField
     * Create special fields in database table.
     * 
     * @param {String} $table
     * Name of table.
     * @param {Mixed} $p1
     * @param {Mixed} $p2
     * @param {Mixed} $p3
     * @param {Mixed} $p4
     * @param {Mixed} $p5
     * @return {void}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    public function schemeCreateExtraField($table, $p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null)
    {
        $table->integer('sort')->unsigned()->nullable();
    }

    /**
     * @method upload
     * File uploading and storing in storages.
     * 
     * @return {Integer}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
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

    /**
     * @method getStubFileDirectory
     * Path to directory of stub (class template) files
     * 
     * @return {String}
     * @member Telenok.Core.Field.FileManyToMany.Controller
     */
    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}