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
        {!!  Form::select('value[app.localeDefault]', \App\Vendor\Telenok\Core\Model\System\Language::all()->pluck('title', 'locale'), $model->value->get('app.localeDefault')) !!}
        <span title="" data-content="{{ $controller->LL('localedefault.description') }}" data-placement="right" data-trigger="hover" data-rel="popover"
              class="help-button" data-original-title="{{ $controller->LL('core::default.tooltip.description') }}">?</span>
    </div>
</div>

<div class="form-group">
    {!!  Form::label("value[app.locales][]", $controller->LL('locales.title'), [ 'class'=>'col-sm-3 control-label no-padding-right' ]) !!}
    <div class="col-sm-9">
        {!!  Form::select("value[app.locales][]", \App\Vendor\Telenok\Core\Model\System\Language::all()->pluck('title', 'locale'),
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
    {!!  Form::label('value[telenok.view.theme][]', $controller->LL('view.theme.title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">

        <?php

            $settingCollect = collect($model->value->get("telenok.view.theme", "default"));

        ?>

        @if ($cnt = count((array)$settingCollect->get('key')))

        <?php

            for ($iterSetting = 0; $iterSetting < $cnt; $iterSetting++)
            {
                $selectedCase = array_get($settingCollect->get('case'), $iterSetting);

            ?>

            <div class="template-key-row-{{$uniqueId}}">

                {!! Form::select('value[telenok.view.theme][key][]',
                    (function($array){
                        $arr = [];
                        foreach($array as $k => $v)
                        {
                            $arr[$v] = $v;
                        }

                        return $arr;
                    })(app('telenok.repository')->getViewTheme()->all()),
                    array_get($settingCollect->get('key'), $iterSetting)) !!}

                {!! Form::select('value[telenok.view.theme][case][]', [
                        'default' => 'default',
                        'url-regex' => 'url-regex',
                        'time-range' => 'time-range',
                        'date-range' => 'date-range',
                        'php' => 'php'
                    ],
                    $selectedCase,
                    ['class' => 'template-select-case']) !!}

                @if ($selectedCase == 'time-range' || $selectedCase == 'date-range')

                    {!!  Form::text('value[telenok.view.theme][value1][]', array_get($settingCollect->get('value1'), $iterSetting),
                        [
                            'placeholder' => ($selectedCase == 'time-range' ? '10:30' : '2020-01-20 10:30')
                        ]) !!}

                    {!!  Form::text('value[telenok.view.theme][value2][]',
                            array_get($settingCollect->get('value2'), $iterSetting),
                            [
                                'class' => 'template-value2',
                                'placeholder' => ($selectedCase == 'time-range' ? '10:30' : '2020-01-20 10:30')
                            ]) !!}

                @elseif ($selectedCase == 'default')

                    {!!  Form::hidden('value[telenok.view.theme][value1][]',
                            array_get($settingCollect->get('value1'), $iterSetting)) !!}

                    {!!  Form::hidden('value[telenok.view.theme][value2][]',
                            array_get($settingCollect->get('value2'), $iterSetting),
                            ['class' => 'template-value2']) !!}

                @else

                    {!!  Form::text('value[telenok.view.theme][value1][]', array_get($settingCollect->get('value1'), $iterSetting)) !!}

                    {!!  Form::hidden('value[telenok.view.theme][value2][]',
                            array_get($settingCollect->get('value2'), $iterSetting),
                            ['class' => 'template-value2']) !!}

                @endif

                <button type="button" class="btn btn-success btn-minier btn-add"><i class="fa fa-plus"></i></button>

                @if ($iterSetting)
                <button type="button" class="btn btn-danger btn-remove btn-minier"><i class="fa fa-minus"></i></button>
                @endif
            </div>

            <?php

            }

            ?>

        @else

            <div class="template-key-row-{{$uniqueId}}">

                {!! Form::select('value[telenok.view.theme][key][]',
                                    (function($array){
                        $arr = [];
                        foreach($array as $k => $v)
                        {
                            $arr[$v] = $v;
                        }

                        return $arr;
                    })(app('telenok.repository')->getViewTheme()->all())
                    , config('telenok.view.theme')) !!}

                {!! Form::select('value[telenok.view.theme][case][]', [
                        'default' => 'default',
                        'url-regex' => 'url-regex',
                        'time-range' => 'time-range',
                        'date-range' => 'date-range',
                        'php' => 'php'
                    ],
                    '',
                    ['class' => 'template-select-case']) !!}

                {!! Form::hidden('value[telenok.view.theme][value1][]', '') !!}
                {!! Form::hidden('value[telenok.view.theme][value2][]', '', ['class' => 'template-value2']) !!}

                <button type="button" class="btn btn-success btn-minier btn-add"><i class="fa fa-plus"></i></button>
            </div>

        @endif

        <template id="template-key-select-{{$uniqueId}}">
            <div class="template-key-row-{{$uniqueId}}">

                {!! Form::select('value[telenok.view.theme][key][]', app('telenok.repository')->getViewTheme()->all(), config('telenok.view.theme')) !!}
                {!! Form::select('value[telenok.view.theme][case][]', [
                        'default' => 'default',
                        'url-regex' => 'url-regex',
                        'time-range' => 'time-range',
                        'date-range' => 'date-range',
                        'php' => 'php'
                    ],
                    '',
                    ['class' => 'template-select-case']) !!}

                {!! Form::hidden('value[telenok.view.theme][value1][]', '') !!}
                {!! Form::hidden('value[telenok.view.theme][value2][]', '', ['class' => 'template-value2']) !!}

                <button type="button" class="btn btn-success btn-add btn-minier"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-remove btn-minier"><i class="fa fa-minus"></i></button>
            </div>
        </template>

        <script>
            jQuery(document).on('click', '.template-key-row-{{$uniqueId}} button.btn-add', function()
            {
                var $topEl = jQuery(this).parent('.template-key-row-{{$uniqueId}}');
                var $template = jQuery("#template-key-select-{{$uniqueId}}").html();

                $topEl.after($template);
            });

            jQuery(document).on('change', '.template-key-row-{{$uniqueId}} select.template-select-case', function()
            {
                var val = jQuery(this).val();
                var parent = jQuery(this).parent('div.template-key-row-{{$uniqueId}}');

                if (val == 'time-range' || val == 'date-range')
                {
                    jQuery('input', parent).attr('type', 'text');

                    if (val == 'time-range')
                    {
                        jQuery('input', parent).attr('placeholder', '10:30');
                    }
                    else
                    {
                        jQuery('input', parent).attr('placeholder', '2020-01-20 10:30');
                    }
                }
                else if (val == 'default')
                {
                    jQuery('input', parent).attr('type', 'hidden');
                    jQuery('input', parent).attr('placeholder', '');
                }
                else
                {
                    jQuery('input', parent).attr('placeholder', '');
                    jQuery('input', parent).attr('type', 'text');
                    jQuery('.template-value2', parent).attr('type', 'hidden');
                }
            });

            jQuery(document).on('click', '.template-key-row-{{$uniqueId}} button.btn-remove', function()
            {
                jQuery(this).parent('.template-key-row-{{$uniqueId}}').remove();
            });
        </script>
    </div>
</div>