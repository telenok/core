
@if ($field->code !== 'structure')

    {!! app('telenok.config.repository')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

@elseif ($field->code === 'structure')

    <?php

    $w = app('telenok.config.repository')->getWidget()->get($model->key);

    ?>

    @if ($w)

    {!! $w->getStructureContent($model, $uniqueId) !!}

    @endif
    
@endif