<?php

	$jsUnique = str_random();

	$viewsCollection = collect(app('telenok.config.repository')->getObjectFieldViewModel()->get($controller->getKey(), []));

	$viewsCollection->push($controller->getViewModel());
	
	if ($model->field_view)
	{
		$viewsCollection->push($model->field_view);
	}

	$views = ['<option>' . $controller->LL('property.default'). '</option>'];

	foreach($viewsCollection->unique()->all() as $v)
	{
		$views[] = "<option value='" . e($v). "' " . ($model->field_view == $v ? 'selected' : '') . ">" . e($v) . "</option>";
	}
?>

<div class="form-group">
	{!! Form::label('key', $controller->LL('property.field_view'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		<select data-placeholder="{{$controller->LL('notice.choose')}}" id="input{{$jsUnique}}" name="field_view">
		 {!! implode('', $views) !!}
		 </select>
		 <script type="text/javascript">
			 jQuery("#input{{ $jsUnique }}").chosen({
				 create_option: true,
				 keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
				 lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
				 width: '350px',
				 search_contains: true
			 });
		 </script>
	</div>
</div>