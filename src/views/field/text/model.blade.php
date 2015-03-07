<?php

	$disabled = false; 
    $domAttr = ['class' => $field->css_class?: 'form-control'];
    $jsUnique = str_random();

	if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

?>

@if ($field->multilanguage)

<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4 class="row">
			<span class="col-sm-12">
				<i class="ace-icon fa fa-list-ul"></i>
				{{ $field->translate('title_list') }}
			</span>
		</h4>
	</div>
	<div class="widget-body"> 
		<div class="widget-main form-group field-list">
			<ul class="nav nav-tabs" >
				<?php
				$localeDefault = \Config::get('app.localeDefault');

				$languages = \App\Model\Telenok\System\Language::whereIn('locale', \Config::get('app.locales')->all())
								->get()->sortBy(function($item) use ($localeDefault)
				{
					return $item->locale == $localeDefault ? 0 : 1;
				});
				?>

				@foreach($languages as $language)
				<li class="<?php if ($language->locale == $localeDefault) echo "active"; ?>">
					<a data-toggle="tab" href="#{{$jsUnique}}-language-{{$language->locale}}-{{$field->code}}">
						{{$language->translate('title')}}
					</a>
				</li>
				@endforeach

			</ul>
			<div class="tab-content">
				@foreach($languages as $language)
                
                <?php
                    
                    $domAttr['id'] = $field->code . '-' . $uniqueId . '-' . $language->locale; 
                
                ?>
                
				<div id="{{$jsUnique}}-language-{{$language->locale}}-{{$field->code}}" class="tab-pane in <?php if ($language->locale == $localeDefault) echo "active"; ?>">
					{!! Form::textarea("{$field->code}[{$language->locale}]", $model->translate($field->code, $language->locale), $domAttr ) !!}
				</div>
				@endforeach
			</div> 

		</div>

	</div>
</div>
@else

<?php

    $domAttr['id'] = $field->code . '-' . $uniqueId; 

?>

<div class="form-group">
	{!! Form::label("{$field->code}", $field->translate('title'), array('class'=>'control-label')) !!}
	<div class="controls">
		{!! Form::textarea($field->code, $model->translate($field->code), $domAttr) !!}
	</div>
</div>
@endif