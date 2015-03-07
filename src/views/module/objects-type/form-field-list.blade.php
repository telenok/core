{!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

@if ($field->code == 'code' && !$model->exists)

<div class="form-group">
	{!! Form::label("multilanguage", $controller->LL('field.multilanguage'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        <div>
            <div data-toggle="buttons" class="btn-group btn-overlap">
                <label class="btn btn-white btn-sm btn-primary  active ">
                    <input type="radio" value="0" name="multilanguage"> {{$controller->LL('btn.no')}}
                </label>
                <label class="btn btn-white btn-sm btn-primary ">
                    <input type="radio" value="1" name="multilanguage"> {{$controller->LL('btn.yes')}}
                </label>
            </div>
        </div>
    </div>
</div>

@endif 