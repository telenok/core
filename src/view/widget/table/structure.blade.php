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

<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4 class="row">
			<span class="col-sm-12">
				<i class="ace-icon fa fa-list-ul"></i>
				{{ $controller->LL('title.view') }}
			</span>
		</h4>
	</div>
	<div class="widget-body"> 
		<div class="widget-main form-group field-list">
            
            <div class="form-group">
                <div class="col-sm-12"> 
                    {!!  Form::textarea("template_content", $controller->getViewContent(), ['class' => 'form-control']) !!}
                </div>
            </div>
		</div>
	</div>
</div>