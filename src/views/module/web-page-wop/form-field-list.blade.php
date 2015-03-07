
@if ($field->code !== 'structure')

    {!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

@elseif ($field->code === 'structure')

    <?php

    $w = app('telenok.config')->getWidget()->get($model->key);

    ?>

    @if ($w)

    {!! $w->getStructureContent($model, $uniqueId) !!}

    @endif
    
@endif