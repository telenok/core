<div class="form-group">
    {!!  Form::label('value[app.backend.brand]', $controller->LL('app.backend.brand.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.backend.brand]',  $model->value->get('app.backend.brand')) !!}
        <span title="" data-content="{{ $controller->LL('app.backend.brand.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[app.localeDefault]', $controller->LL('localedefault.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::select('value[app.localeDefault]', \App\Telenok\Core\Model\System\Language::all()->lists('title', 'locale'), $model->value->get('app.localeDefault')) !!}
        <span title="" data-content="{{ $controller->LL('localedefault.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label("value[app.locales][]", $controller->LL('locales.title'), [ 'class'=>'col-sm-3 control-label no-padding-right' ]) !!}
    <div class="col-sm-9">
        {!!  Form::select("value[app.locales][]", \App\Telenok\Core\Model\System\Language::all()->lists('title', 'locale'),
            $model->value->get('app.locales', []), [ 'multiple'=>'multiple', 'size'=>10 ] ) !!}
        <span title="" data-content="{{ $controller->LL('locales.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[app.timezone]', $controller->LL('timezone.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.timezone]',  $model->value->get('app.timezone')) !!}
        <span title="" data-content="{{ $controller->LL('timezone.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[telenok.view.theme]', $controller->LL('view.theme.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::select('value[telenok.view.theme]', app('telenok.config.repository')->getViewTheme()->all(), config('telenok.view.theme')) !!}
        <span title="" data-content="{{ $controller->LL('view.theme.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<?php

$listViews = collect();

\Event::fire('telenok.module.setting.' . $controller->getKey() . '.content', [$listViews]);

?>

@foreach($listViews->all() as $v)

{!! $v !!}

@endforeach
    
