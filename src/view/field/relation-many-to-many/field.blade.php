
@include('core::field.common-view.field-view')

<?php

    $jsUnique = str_random();

    if (!$model->exists || $model->relation_many_to_many_has || !$model->relation_many_to_many_belong_to)
    {
        $linkedField = 'relation_many_to_many_has';
    }
    else
    {
        $linkedField = 'relation_many_to_many_belong_to';
    }
?>

<div class="form-group">
	{!! Form::label("required", $controller->LL('property.required'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div data-toggle="buttons" class="btn-group btn-overlap">
            <label class="btn btn-white btn-sm btn-primary @if (!$model->required) active @endif">
                <input type="radio" value="0" name="required" @if (!$model->required) checked="checked" @endif> {{$controller->LL('btn.no')}}
            </label>

            <label class="btn btn-white btn-sm btn-primary @if ($model->required) active @endif">
                <input type="radio" value="1" name="required" @if ($model->required) checked="checked" @endif> {{$controller->LL('btn.yes')}}
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label($linkedField, $controller->LL('entity.'.$linkedField), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        <?php 

            $domAttr = [];

			if ($model->{$linkedField})
            {
                $domAttr['disabled'] = 'disabled';
            }

        ?>
        {!! Form::hidden($linkedField, $model->{$linkedField}) !!}
        {!! Form::select($linkedField, \App\Telenok\Core\Model\Object\Type::get(['title', 'id'])->transform(function($item) { return ['title' => $item->translate('title'), 'id' => $item->id]; })->sortBy('title')->lists('title', 'id'), $model->{$linkedField}, $domAttr) !!}
    </div>
</div>


@if ($model->{$linkedField})
    <div class="form-group">
        {!! Form::label('relation_many_to_many_default', $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
        <div class="col-sm-9">
            <select class="chosen-select" multiple data-placeholder="{{$controller->LL('notice.choose')}}" 
                    id="relation_many_to_many_default-{{$jsUnique}}" name="relation_many_to_many_default[]">

                <option></option>

            <?php

                $subjects = \App\Telenok\Core\Model\Object\Sequence::getModelByTypeId($model->{$linkedField})
                    ->active()->withPermission()
                    ->whereIn($model->getKeyName(), $model->relation_many_to_many_default->all())
                    ->get(['id', 'title']);

                foreach ($subjects as $subject) 
                {
                    echo "<option value='{$subject->getKey()}' selected='selected'>[#{$subject->id}] {$subject->translate('title')}</option>";
                }
            ?>
            </select>
            <script type="text/javascript">
                jQuery("#relation_many_to_many_default-{{$jsUnique}}").ajaxChosen({
                    keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                    lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                    type: "GET",
                    url: "{!! route($controller->getRouteListTitle(), ['id' => (int)$model->{$linkedField}]) !!}",
                    dataType: "json",
                    minTermLength: 1
                },
                function (data)
                {
                    var results = [];

                    jQuery.each(data, function (i, val) 
                    {
                        results.push({value: val.value, text: val.text});
                    });

                    return results;
                },
                {
                    width: "300px",
                    no_results_text: "{{ $controller->LL('notice.not-found') }}"
                });
            </script>
        </div>
    </div>
@endif 