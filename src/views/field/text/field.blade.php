<div class="form-group">
    {!! Form::label('text_width', $controller->LL('property.width'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text('text_width', $model->text_width) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('text_height', $controller->LL('property.height'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text('text_height', $model->text_height) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label("text_default", $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::textarea("text_default", $model->text_default, ['class'=>'col-md-4', 'style' => 'height:60px;']) !!}
    </div>
</div>

