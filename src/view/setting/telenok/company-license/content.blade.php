<div class="form-group">
    {!!  Form::label('value[app.backend.brand]', $controller->LL('app.backend.brand.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.backend.brand]',  $model->value->get('app.backend.brand')) !!}
        <span title="" data-content="{{ $controller->LL('app.backend.brand.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[app.social.twitter.url]', $controller->LL('app.social.twitter.url.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.social.twitter.url]',  $model->value->get('app.social.twitter.url')) !!}
        <span title="" data-content="{{ $controller->LL('app.social.twitter.url.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[app.social.facebook.url]', $controller->LL('app.social.facebook.url.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.social.facebook.url]',  $model->value->get('app.social.facebook.url')) !!}
        <span title="" data-content="{{ $controller->LL('app.social.facebook.url.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[app.social.vk.url]', $controller->LL('app.social.vk.url.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.social.vk.url]',  $model->value->get('app.social.vk.url')) !!}
        <span title="" data-content="{{ $controller->LL('app.social.vk.url.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[app.social.youtube.url]', $controller->LL('app.social.youtube.url.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[app.social.youtube.url]',  $model->value->get('app.social.youtube.url')) !!}
        <span title="" data-content="{{ $controller->LL('app.social.youtube.url.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 
