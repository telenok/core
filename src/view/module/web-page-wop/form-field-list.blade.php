
@if ($field->code !== 'structure')

    {!! app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

@elseif ($field->code === 'structure')

    <?php

    $w = app('telenok.repository')->getWidget()->get($model->key);

    ?>

    @if ($w)

    {!! $w->getStructureContent($model, $uniqueId) !!}

    @endif
    
@endif