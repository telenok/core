<div class="form-group">
	{!!  Form::label("template_content", $controller->LL('title.view'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9"> 
        {!!  Form::textarea("template_content", $controller->getViewContent(), ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!!  Form::label("structure[menu_type]", $controller->LL('title.menu_type'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9"> 
        {!!  Form::select("structure[menu_type]", [
                '1' => $controller->LL('title.menu_type.1'),
                '2' => $controller->LL('title.menu_type.2'),
            ], $model->structure->get('menu_type', 1), ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!!  Form::label("node_ids", $controller->LL('title.node_ids'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9"> 
        {!!  Form::text("structure[node_ids]", $model->structure->get('node_ids', ''), ['class' => 'form-control']) !!}
	</div>
</div>