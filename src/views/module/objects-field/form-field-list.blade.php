	@if (!in_array($field->code, ['key', 'field_view'], true))

		{!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

	@elseif ($field->code == "field_view" && $model->exists)

        <?php

            $views = app('telenok.config')->getObjectFieldViewModel()->get($model->key, []);

            if (empty($viewsCollection))
            {
                $views[] = app('telenok.config')->getObjectFieldController()->get($model->key)->getViewModel();
            }
            
            $views = array_combine($views, $views);
        ?>

		<div class="form-group">
			{!! Form::label('key', $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
			<div class="col-sm-9">
                {!! Form::select($field->code, $views, $model->{$field->code}) !!}
			</div>
		</div>

	@elseif ($field->code=='key')

		{!! Form::hidden('key', $model->{$field->code}) !!}

		<div class="form-group">
			{!! Form::label('key', $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
			<div class="col-sm-9">

				<?php 

				$key = ['onchange' => "onChangeType{$uniqueId}()"];

				if ($model->exists)
				{
					$key['disabled'] = 'disabled';
				}

				$selectFields = [];
				$multilanguageFields = [];

				app('telenok.config')->getObjectFieldController()
                        ->reject(function($i) { return in_array($i->getKey(),['locked-by', 'deleted-by', 'created-by', 'active', 'permission', 'updated-by'], true); })
                        ->each(function($field) use (&$selectFields, &$multilanguageFields) 
				{  
					$selectFields[$field->getKey()] = $field->getName(); 

					if ($field->allowMultilanguage())
					{
						$multilanguageFields[] = $field->getKey();
					}
				});

				?>
			{!! Form::select('key', $selectFields, $model->{$field->code}, $key) !!}
			</div>
		</div>

		<script type="text/javascript">
			function onChangeType{{$uniqueId}}()
			{
				var $form = jQuery('#model-ajax-{{$uniqueId}}');

				var $key = jQuery('select[name="key"]', $form);

				@if (!$model->exists)

				if ( ['{{implode("','", $multilanguageFields)}}'].join(',').indexOf($key.val())>=0 )
				{
					jQuery('input[name="multilanguage"][type="checkbox"]', $form).removeAttr('disabled');
				}
				else
				{
					jQuery('input[name="multilanguage"][type="checkbox"]', $form).attr('disabled', 'disabled');
				}

				@endif
			} 
            
            onChangeType{{$uniqueId}}();
		</script>

		@if ($model->exists) 

		{!! app('telenok.config')->getObjectFieldController()->get($model->key)->getFormFieldContent($model, $uniqueId) !!}

		@endif

	@endif 