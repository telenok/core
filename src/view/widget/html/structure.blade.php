<div class="form-group">
	{!!  Form::label("template_content", $controller->LL('title.view'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9"> 
        {!!  Form::textarea("template_content", $controller->getViewContent(), ['class' => 'form-control']) !!}
	</div>
</div>