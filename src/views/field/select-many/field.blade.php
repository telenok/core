
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

<template id='template-select-many-{{$uniqueId}}'>
    <?php
        $localeDefault = \Config::get('app.localeDefault');

        $languages = \App\Model\Telenok\System\Language::whereIn('locale', \Config::get('app.locales')->all())
                        ->get()->sortBy(function($item) use ($localeDefault)
        {
            return $item->locale == $localeDefault ? 0 : 1;
        });
    ?>
    <div class="widget-box container-select-many collapsed">
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
            <div class="widget-main">
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
                                <input type="text" value="" name="select_many_data[title][{{$language->locale}}][]" class="title-{{$language->locale}} col-xs-12 col-sm-12">
                            </div>
                            @endforeach
                        </div> 
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="select_many_data[key][]">{{$controller->LL('row.title.key')}}</label>
                        <div class="col-sm-9 select-many-group">
                            <input type="text" name="select_many_data[key][]" value="" placeholder="Key value" class="input-large key-value" />
                            <label class="inline">
                                <input type="hidden" class="key-default-hidden" name="select_many_data[default][]" value="" />
                                <input type="checkbox" class="ace key-default" />
                                <span class="lbl"> {{$controller->LL('row.title.key.default')}}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<div class="widget-box transparent field-select-many-{{$uniqueId}}">
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
            <div class="col-sm-12 select-many-container"> 
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    <?php
        $selectManyData = $model->select_many_data->all();
        $allKeys = $model->select_many_data->get('key', []);
        $default = $model->select_many_data->get('default', []);
    ?>

    var data{{$uniqueId}} = []; 

    @foreach($allKeys as $k => $key)

        var insert = {
            'title': {},
            'key': "{{$key}}",
            'default': '{{(in_array($key, $default, true) ? $key : "")}}',
        };

        @foreach($languages as $l)

            insert['title']["{{$l->locale}}"] = "{{array_get($selectManyData, 'title.'.$l->locale.'.'.$k)}}";

        @endforeach

        data{{$uniqueId}}.push(insert);

    @endforeach

    var $template = jQuery("#template-select-many-{{$uniqueId}}");

    function addSelectManyRow{{$uniqueId}}($obj)
    {
        var $templateClone = jQuery($template.html().trim());

        if ($obj)
        {
            $obj.after($templateClone);
        }
        else
        {
            jQuery('div.field-select-many-{{$uniqueId}} div.select-many-container').append($templateClone);
        }

        var randId = Math.floor(Math.random()*1000000000);

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
            if (this.checked)
            {
                var v = jQuery("input.key-value", $templateClone).val();
                jQuery("input.key-default-hidden", $templateClone).val(v);
            }
            else
            {
                jQuery("input.key-default-hidden", $templateClone).val("");
            }
            
            jQuery("div.widget-header .widget-title", $templateClone).toggleClass('green'); 
        });

        jQuery("input.key-value", $templateClone).on('keyup', function()
        {
            jQuery(".widget-title", $templateClone).text("{{$controller->LL('row.title')}}" + this.value);
              
            if (jQuery("input.key-default", $templateClone).prop('checked'))
            {
                jQuery("input.key-default-hidden", $templateClone).val(this.value);
            }
        }); 

        jQuery('div.widget-header a[data-action="close"]', $templateClone).on('click', function()
        {
            if (jQuery('div.field-select-many-{{$uniqueId}} div.container-select-many').size() == 1)
            {
                addSelectManyRow{{$uniqueId}}();
            }
        })

        return $templateClone;
    }

    if (data{{$uniqueId}}.length)
    {
        data{{$uniqueId}}.forEach(function(o) 
        {
            $templateClone = addSelectManyRow{{$uniqueId}}();

            for(var key in o.title)
            {
                 jQuery("input[name='select_many_data\[title\]\[" + key + "\]\[\]']", $templateClone).val(o.title[key]);
            }

            jQuery(".widget-title", $templateClone).text("{{$controller->LL('row.title')}}" + o.key); 

            jQuery("input.key-value", $templateClone).val(o.key);

            if (o.default === o.key)
            {
                jQuery("input.key-default", $templateClone).attr('checked', 'checked');
                jQuery("input.key-default-hidden", $templateClone).val(o.key);
                jQuery("div.widget-header .widget-title", $templateClone).addClass('green'); 
            }
        });
    }
    else
    {
        addSelectManyRow{{$uniqueId}}();
    }

    jQuery('div.field-select-many-{{$uniqueId}} div.select-many-container').sortable({ 
        axis: "y",
        items: "div.container-select-many",
        containment: "parent",
        delay: 150,
        cursor: "move",
        handle: ".widget-header"
    }); 

    jQuery('div.field-select-many-{{$uniqueId}}').on('click', 'a[data-action="clone-row"]', function()
    {
        var $widgetThis = jQuery(this).closest('div.container-select-many');

        addSelectManyRow{{$uniqueId}}($widgetThis);
    });

</script>
