<?php

    $user = null;
    
    if ($model->exists)
    {
        $user = $model->updatedByUser()->first();
    }
?>

<div class="form-group">
	{!! Form::label(str_random(), $controller->LL('title'), array('class'=>'col-xs-2 control-label text-right')) !!}
	<div class="col-xs-10">
		<div class="input-group">
			<span class="input-group-addon">
				<i class="fa fa-calendar bigger-110"></i>
			</span>
			<input type="text" disabled="disabled" value="@if ($model->exists){{$model->updated_at->setTimezone(config('app.timezone'))}}@endif" />
			<label class="inline"><span class="lbl">&nbsp;{{ $controller->LL('by') }}&nbsp;</span></label>
			<input type="text" disabled="disabled" value="@if ($user) [{{$user->getKey()}}] {{$user->title}} @endif" title="@if ($user) [{{$user->getKey()}}] {{$user->title}} @endif" />
		</div>
	</div>
</div>