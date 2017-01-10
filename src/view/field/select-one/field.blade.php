
@include('core::field.common-view.field-view')

<div class="form-group">
	{!! Form::label("required", $controller->LL('property.required'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div data-toggle="buttons" class="btn-group btn-overlap">
            <label class="btn btn-white btn-sm btn-primary @if (!$model->required) active @endif">
                <input type="radio" value="0" name="required" @if (!$model->required) checked="checked" @endif> {{$controller->LL('btn.no')}}
            </label>

            <label class="btn btn-white btn-sm btn-primary @if ($model->required) active @endif">
                <input type="radio" value="1" name="required" @if ($model->required) checked="checked" @endif> {{$controller->LL('btn.yes')}}
            </label>
        </div>
    </div>
</div>

<template id='template-select-one-{{$uniqueId}}'>
    <?php
        $localeDefault = config('app.localeDefault');

        $languages = \App\Vendor\Telenok\Core\Model\System\Language::whereIn('locale', config('app.locales'))
                        ->get()->sortBy(function($item) use ($localeDefault)
        {
            return $item->locale == $localeDefault ? 0 : 1;
        });
    ?>
    <div class="widget-box container-select-one collapsed">
        <div class="widget-header widget-header-flat" style="cursor: move;">
            <h4 class="widget-title">{{$controller->LL('row.title')}}</h4>

            <div class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="ace-icon fa fa-chevron-down"></i>
                </a>
                <a data-action="clone-row" href="#">
                    <i class="ace-icon fa light-green fa-plus"></i>
                </a>
                <a data-action="close" href="#">
                    <i class="ace-icon fa fa-times"></i>
                </a>
            </div>
        </div>

        <div class="widget-body">
            <div class="widget-main many-language-{{$uniqueId}}">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            @foreach($languages as $language)
                            <li class="<?php if ($language->locale == $localeDefault) echo "active"; ?>">
                                <a data-toggle="tab" href="#{{$uniqueId}}-language-{{$language->locale}}-{{$model->code}}">
                                    {{$language->translate('title')}}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($languages as $language)
                            <div class="tab-pane in <?php if ($language->locale == $localeDefault) echo "active"; ?>" id="{{$uniqueId}}-language-{{$language->locale}}-{{$model->code}}">
                                <input type="text" value="" name="select_one_data[title][{{$language->locale}}][]" class="multilanguage-field title-{{$language->locale}} col-xs-12 col-sm-12">
                            </div>
                            @endforeach
                        </div> 
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="select_one_data[key][]">{{$controller->LL('row.title.key')}}</label>
                        <div class="col-sm-9 select-one-group">
                            <input type="text" name="select_one_data[key][]" value="" placeholder="Key value" class="multilanguage-field input-large key-value" />
                            <label class="inline">
                                <input type="checkbox" class="multilanguage-field ace key-default" />
                                <span class="lbl"> {{$controller->LL('row.title.key.default')}}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-main one-language-{{$uniqueId}}">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#{{$uniqueId}}-language">
                                    {{$controller->LL('title.value')}}
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane in active" id="{{$uniqueId}}-language">
                                <input type="text" value="" name="select_one_data[title][]" class="onelanguage-field col-xs-12 col-sm-12">
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="select_one_data[key][]">{{$controller->LL('row.title.key')}}</label>
                        <div class="col-sm-9 select-one-group">
                            <input type="text" name="select_one_data[key][]" value="" placeholder="Key value" class="onelanguage-field input-large key-value" />
                            <label class="inline">
                                <input type="checkbox" class="onelanguage-field ace key-default" />
                                <span class="lbl"> {{$controller->LL('row.title.key.default')}}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<div class="widget-box transparent field-select-one-{{$uniqueId}}">
    <div class="widget-header widget-header-small">
        <h4 class="row">
            <span class="col-sm-12">
                <i class="ace-icon fa  fa-list-ul"></i>
                {{$controller->LL('block.title')}}
            </span>
        </h4>
    </div>
    <div class="widget-body"> 
        <div class="widget-main form-group">
            <input type="hidden" id="key-default-{{$uniqueId}}" name="select_one_data[default]" value="" />
            <div class="col-sm-12 select-one-container"></div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var $form{{$uniqueId}} = jQuery('#model-ajax-{{$uniqueId}}');
    var $multilanguage{{$uniqueId}} = jQuery('input[name=multilanguage]', $form{{$uniqueId}});

    var closureMultilanguage{{$uniqueId}} = function()
    {
        if (jQuery('input:checked[name=multilanguage]', $form{{$uniqueId}}).val() == 1)
        {
            $('.onelanguage-field', $form{{$uniqueId}}).attr('disabled', 'disabled');
            $('.multilanguage-field', $form{{$uniqueId}}).removeAttr('disabled');

            jQuery('.many-language-{{$uniqueId}}', $form{{$uniqueId}}).show();
            jQuery('.one-language-{{$uniqueId}}', $form{{$uniqueId}}).hide();
        }
        else
        {
            $('.multilanguage-field').attr('disabled', 'disabled');
            $('.onelanguage-field').removeAttr('disabled');

            jQuery('.many-language-{{$uniqueId}}', $form{{$uniqueId}}).hide();
            jQuery('.one-language-{{$uniqueId}}', $form{{$uniqueId}}).show();
        }
    };

    <?php
        $selectOneData = $model->select_one_data->all();
        $allKeys = array_keys($model->select_one_data->get('key', []));
    ?>

    var data{{$uniqueId}} = {'title-key':[], 'default': "{{ array_get($selectOneData, 'default') }}" }; 

    @foreach($allKeys as $key)

        var insert = {
            'title': {},
            'key': "{{array_get($selectOneData, 'key.'.$key)}}",
        };

        @if ($model->multilanguage)

            @foreach($languages as $l)

                insert['title']["{{$l->locale}}"] = "{{array_get($selectOneData, 'title.'.$l->locale.'.'.$key)}}";

            @endforeach

        @else
            insert['title']["{{$key}}"] = "{{array_get($selectOneData, 'title.'.$key)}}";
        @endif

        data{{$uniqueId}}['title-key'].push(insert);

    @endforeach

    var $template = jQuery("#template-select-one-{{$uniqueId}}");

    function addSelectOneRow{{$uniqueId}}($obj)
    {
        var $templateClone = jQuery($template.html().trim());

        if ($obj)
        {
            $obj.after($templateClone);
        }
        else
        {
            jQuery('div.field-select-one-{{$uniqueId}} div.select-one-container').append($templateClone);
        }

        var randId = Math.floor(Math.random()*10000000);

        jQuery('ul.nav.nav-tabs li a, div.tab-content div.tab-pane.in', $templateClone).each(function(k, v)
        {
            if (v.tagName.toLowerCase() == 'a')
            {
                v.href += randId;
            }
            else if (v.tagName.toLowerCase() == 'div')
            {
                v.id += randId;
            }
        });

        jQuery("input.key-default", $templateClone).on('click', function()
        {
            jQuery("div.field-select-one-{{$uniqueId}} input.key-default").not(this).removeAttr('checked').val(0);
            jQuery("div.field-select-one-{{$uniqueId}} div.widget-header .widget-title").removeClass('green');

            var $i = jQuery("input.key-value", $templateClone);
            var $c = jQuery("input.key-default", $templateClone);

            if (this.checked)
            {
                jQuery("#key-default-{{$uniqueId}}").val($i.val());
                $c.prop('checked', 'checked');
                jQuery("div.widget-header .widget-title", $templateClone).addClass('green');
            }
            else
            {
                jQuery("#key-default-{{$uniqueId}}").val("");
                $c.removeProp('checked');
            }
        });

        jQuery("input.key-value", $templateClone).on('keyup', function()
        {
            jQuery("h4.widget-title", $templateClone).text("{{$controller->LL('row.title')}}" + this.value);

            jQuery("input.key-value", $templateClone).val(this.value);
        });

        jQuery('div.widget-header a[data-action="close"]', $templateClone).on('click', function()
        {
            if (jQuery('div.field-select-one-{{$uniqueId}} div.container-select-one').size() == 1)
            {
                addSelectOneRow{{$uniqueId}}();
            }
        });

        closureMultilanguage{{$uniqueId}}();

        return $templateClone;
    }

    if (data{{$uniqueId}}['title-key'].length)
    {
        jQuery("#key-default-{{$uniqueId}}").val(data{{$uniqueId}}.default);

        data{{$uniqueId}}['title-key'].forEach(function(o) 
        {
            $templateClone = addSelectOneRow{{$uniqueId}}();

            for(var key in o.title)
            {
                @if ($model->multilanguage)
                    jQuery("input[name='select_one_data\[title\]\[" + key + "\]\[\]']", $templateClone).val(o.title[key]);
                @else
                    jQuery("input[name='select_one_data\[title\]\[\]']", $templateClone).val(o.title[key]);
                @endif
            }

            jQuery("h4.widget-title", $templateClone).text("{{$controller->LL('row.title')}}" + o.key); 

            jQuery("input.key-value", $templateClone).val(o.key);

            if (data{{$uniqueId}}.default == o.key)
            {
                jQuery("input.key-default", $templateClone).attr('checked', 'checked');
                jQuery("div.widget-header .widget-title", $templateClone).addClass('green');
            }
        });
    }
    else
    {
        addSelectOneRow{{$uniqueId}}();
    }

    closureMultilanguage{{$uniqueId}}();

    $multilanguage{{$uniqueId}}.change(function() { closureMultilanguage{{$uniqueId}}(); });


    jQuery('div.field-select-one-{{$uniqueId}} div.select-one-container').sortable({ 
        axis: "y",
        items: "div.container-select-one",
        containment: "parent",
        delay: 150,
        cursor: "move",
        handle: ".widget-header"
    }); 

    jQuery('div.field-select-one-{{$uniqueId}}').on('click', 'a[data-action="clone-row"]', function()
    {
        var $widgetThis = jQuery(this).closest('div.container-select-one');

        addSelectOneRow{{$uniqueId}}($widgetThis);
    });

</script>
