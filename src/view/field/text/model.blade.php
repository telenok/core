<?php
$disabled = false;
$domAttr = ['class' => $field->css_class? : 'form-control', 'style' => ''];
$jsUnique = str_random();

if ($field->text_width)
{
    $domAttr['style'] .= 'width:' . e($field->text_width) . ';';
}

if ($field->text_height)
{
    $domAttr['style'] .= 'height:' . e($field->text_height) . ';';
}

if ((!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)))
{
    $domAttr['disabled'] = 'disabled';
    $disabled = true;
}
?>

@if ($field->multilanguage)

<div class="widget-box transparent" data-field-key='{{ $field->code }}'>
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
            <ul class="nav nav-tabs">

                <?php
                $localeDefault = config('app.localeDefault');

                $languages = \App\Telenok\Core\Model\System\Language::whereIn('locale', config('app.locales')->all())
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

                    if ($v = $model->translate($field->code, $language->locale))
                    {
                        $value = $v;
                    }
                    else if (!$model->exists)
                    {
                        $value = $field->translate('text_default', $language->locale);
                    }
                    else
                    {
                        $value = '';
                    }
                ?>
                <div id="{{$jsUnique}}-language-{{$language->locale}}-{{$field->code}}" class="tab-pane in <?php if ($language->locale == $localeDefault) echo "active"; ?>">
                    {!! Form::textarea("{$field->code}[{$language->locale}]", $value, $domAttr ) !!}

                    @if ($field->text_rte) 
                    <script>
                        CKEDITOR.replace('{{$domAttr['id']}}');
                        CKEDITOR.instances['{{$domAttr['id']}}'].on("change", function(e) {
                        CKEDITOR.instances['{{$domAttr['id']}}'].updateElement();
                        });
                    </script>
                    @endif
                </div>
                @endforeach
            </div> 
        </div>
    </div>
</div>
@else

    <?php
        $domAttr['id'] = $field->code . '-' . $uniqueId;

        if ($v = $model->{$field->code})
        {
            $value = $v;
        }
        else if (!$model->exists)
        {
            $value = $field->text_default;
        }
        else
        {
            $value = '';
        }
    ?>

<div class="widget-box transparent">
    <div class="widget-header widget-header-small">
        <h4 class="row">
            <span class="col-sm-12">
                <i class="ace-icon fa fa-list-ul"></i>
                {{ $field->translate('title') }}
            </span>
        </h4>
    </div>
    <div class="widget-body">
        <div class="widget-main form-group field-list" data-field-key='{{ $field->code }}'>
            <div class="col-sm-12">
                <div class="controls">
                    {!! Form::textarea($field->code, $value, $domAttr) !!}

                    @if ($field->text_rte) 
                    <script>
                        CKEDITOR.replace('{{$domAttr['id']}}');
                        CKEDITOR.instances['{{$domAttr['id']}}'].on("change", function(e) {
                            CKEDITOR.instances['{{$domAttr['id']}}'].updateElement();
                        });
                    </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif