<?php 
    
    $domAttr = ['class' => $field->css_class?: ''];
    $disabled = false;
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
				<?php 

					$domAttr['class'] = $field->css_class?: 'col-xs-12 col-sm-12';
				?>

				@foreach($languages as $language)
				<div id="{{$jsUnique}}-language-{{$language->locale}}-{{$field->code}}" class="tab-pane in @if ($language->locale == $localeDefault) active @endif">

					@if ($field->icon_class)
					<span class="input-group-addon">
						<i class="{{ $field->icon_class }}"></i>
					</span>
					@endif

					<?php
                        $domAttr['id'] = $field->code . '-' . $uniqueId . '-' . $language->locale; 
						$domAttr['placeholder'] = ($placeholder = $field->translate('string_default', $language->locale)) ? $placeholder : $field->translate('title', $language->locale);
					?>

					@if ($field->string_password)
						{!! Form::password("{$field->code}[{$language->locale}]", $domAttr ) !!}
					@else
						{!! Form::text("{$field->code}[{$language->locale}]", $model->translate($field->code, $language->locale), $domAttr ) !!}
					@endif

					@if ($field->translate('description'))
					<span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
						  class="help-button" data-original-title="{{\Lang::get('core::default.tooltip.description')}}">?</span>
					@endif

				</div>
				@endforeach
			</div> 

		</div>
	</div>
</div>
@else

<div class="form-group">

	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}

	<?php

        $domAttr['id'] = $field->code . '-' . $uniqueId;
		$domAttr['class'] = $field->css_class ?: 'form-control';
		$domAttr['placeholder'] = ($placeholder = $field->string_default) ? $placeholder : $field->translate('title');

	?>

	<div class="col-sm-9">

            @if ($field->icon_class)
		<div class="input-group">
            <span class="input-group-addon">
                <i class="{{ $field->icon_class }}"></i>
            </span>
            @else
		<div>
            @endif	
            
            @if ($field->string_password)
                <?php 
                    $domAttr['autocomplete'] = "off";
                ?>
                {!! Form::password($field->code, $domAttr) !!}
            @else
                {!! Form::text($field->code, $model->translate($field->code), $domAttr) !!}
            @endif 

            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{\Lang::get('core::default.tooltip.description')}}">?</span>
            @endif
            
		</div>
	</div> 
</div>
@endif


