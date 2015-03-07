<div class="form-group">
	{!!  Form::label("structure[col]", $controller->LL('title.col'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!!  Form::text("structure[col]", $model->structure->get('col')) !!}
	</div>
</div>

<div class="form-group">
	{!!  Form::label("structure[row]", $controller->LL('title.row'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!!  Form::text("structure[row]", $model->structure->get('row')) !!}
	</div>
</div>

<div class="form-group">
	{!!  Form::label("template_content", $controller->LL('title.view'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9"> 
        {!!  Form::textarea("template_content", $controller->getViewContent(), ['class' => 'form-control']) !!}
	</div>
</div>
