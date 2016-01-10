<div class="form-group">
    {!!  Form::label('value[auth.password.length-min]', $controller->LL('auth.password.length-min.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[auth.password.length-min]',  $model->value->get('auth.password.length-min')) !!}
        <span title="" data-content="{{ $controller->LL('auth.password.length-min.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

<div class="form-group">
    {!!  Form::label('value[auth.logout.period]', $controller->LL('auth.logout.period.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">
        {!!  Form::text('value[auth.logout.period]', $model->value->get('auth.logout.period')) !!}
        <span title="" data-content="{{ $controller->LL('auth.logout.period.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div> 

    <div class="form-group" data-field-key='app.acl.enabled'>
    {!!  Form::label('value[app.acl.enabled]', $controller->LL('app.acl.enabled.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
	<div class="col-sm-9">
        <div>
            <div class="btn-group btn-overlap" data-toggle="buttons">
                <label class="btn btn-white btn-sm btn-primary @if ($model->value->get('app.acl.enabled') == 0) active @endif">
                    {!! Form::radio('value[app.acl.enabled]', 0, $model->value->get('app.acl.enabled') == 0) !!} 
                    {{ $controller->LL('btn.no') }}
                </label>
                <label class="btn btn-white btn-sm btn-primary @if ($model->value->get('app.acl.enabled') == 1) active @endif">
                    {!! Form::radio('value[app.acl.enabled]', 0, $model->value->get('app.acl.enabled') == 1) !!} 
                    {{ $controller->LL('btn.yes') }}
                </label>
            </div>
            <span title="" data-content="{{ $controller->LL('app.acl.enabled.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description')}}">?</span>
        </div>
    </div>
</div>

<div class="form-group" data-field-key='app.version.enabled'>
    {!!  Form::label('value[app.version.enabled]', $controller->LL('app.version.enabled.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
	<div class="col-sm-9">
        <div>
            <div class="btn-group btn-overlap" data-toggle="buttons">
                <label class="btn btn-white btn-sm btn-primary @if ($model->value->get('app.version.enabled') == 0) active @endif">
                    {!! Form::radio('value[app.version.enabled]', 0, $model->value->get('app.version.enabled') == 0) !!} 
                    {{ $controller->LL('btn.no') }}
                </label>
                <label class="btn btn-white btn-sm btn-primary @if ($model->value->get('app.version.enabled') == 1) active @endif">
                    {!! Form::radio('value[app.version.enabled]', 0, $model->value->get('app.version.enabled') == 1) !!} 
                    {{ $controller->LL('btn.yes') }}
                </label>
            </div>
            <span title="" data-content="{{ $controller->LL('app.version.enabled.description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description')}}">?</span>
        </div>
    </div>
</div>