<?php 
	$domAttr = ['class'=>'ace ace-switch ace-switch-3'];
	$disabled = false;
	$value = 1;
    $jsUnique = str_random();
	/*
	if (!$model->exists) 
	{
		$value = $field->checkbox_default;
	}
	else
	{
		$value = $model->{$field->code};
	}

	if ( (!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update) )
	{
		$domAttr['disabled'] = 'disabled';
		$disabled = true;
	}
	*/
?>  

<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4>
			<i class="fa fa-list-ul"></i>
			{{ $field->translate('title_list') }}
		</h4> 
	</div>
	<div class="widget-body"> 
		<div class="widget-main">

			<ul class="nav nav-tabs" id="field-tabs-{{$jsUnique}}-permission">
				@foreach($permissions as $permission) 
				<li><a href="#{{$permission->code . $jsUnique}}" data-toggle="tab">{{$permission->translate('title')}}</a></li>
				@endforeach
			</ul>

			<div class="tab-content" style="overflow: visible;">
				@foreach($permissions as $permission) 
				<div class="tab-pane active" id="{{$permission->code . $jsUnique}}">
					<div class="controls" style="margin-left: 0;">
						<select class="chosen" multiple data-placeholder="{{$controller->LL('notice.choose')}}" id="permission-{{$permission->code . $jsUnique}}" name="permission[{{$permission->code}}][]">
							<?php

								$sequence = new \App\Model\Telenok\Object\Sequence();
								$spr = new \App\Model\Telenok\Security\SubjectPermissionResource();
								$type = new \App\Model\Telenok\Object\Type();

								$sequence->addMultilanguage('title_type');

								$subjects = \App\Model\Telenok\Object\Sequence::select($sequence->getTable() . '.id', $sequence->getTable() . '.title', $type->getTable() . '.title as title_type')
								->join($spr->getTable(), function($query) use ($spr, $sequence, $model) 
								{
									$query->on($sequence->getTable() . '.id', '=', $spr->getTable() . '.acl_subject_object_sequence');
								})
								->join($type->getTable(), function($query) use ($sequence, $type) 
								{
									$query->on($sequence->getTable() . '.sequences_object_type', '=', $type->getTable() . '.id');
								})
								->whereNotNull($spr->getTable() . '.active')
								->where($spr->getTable() . '.acl_resource_object_sequence', $model->getKey())
								->where($spr->getTable() . '.acl_permission_object_sequence', $permission->getKey())
								->get();

								foreach($subjects as $subject)
								{
									echo "<option value='{$subject->getKey()}' selected='selected'>[{$subject->translate('title_type')}#{$subject->id}] {$subject->translate('title')}</option>";
								}
							?>
						</select>
					</div>
				</div>

				<script type="text/javascript">
					jQuery('ul#field-tabs-{{$jsUnique}}-permission a:first').tab('show'); 

					jQuery("#permission-{{$permission->code . $jsUnique}}").ajaxChosen({ 
						keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
						lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
						type: "GET",
						url: "{!! URL::route("cmf.field.permission.list.title") !!}", 
						dataType: "json",
						minTermLength: 1
					}, 
					function (data) 
					{
						var results = [];

						jQuery.each(data, function (i, val) {
							results.push({ value: val.value, text: val.text });
						});

						return results;
					},
					{
						width: "100%",
						no_results_text: "{{ $controller->LL('notice.not-found') }}"
					});

				</script>
				@endforeach
			</div>

		</div>
	</div>
</div>