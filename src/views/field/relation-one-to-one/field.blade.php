
<?php
    if (!$model->exists || $model->relation_one_to_one_has || !$model->relation_one_to_one_belong_to)
    {
        $linkedField = 'relation_one_to_one_has';
    }
    else
    {
        $linkedField = 'relation_one_to_one_belong_to';
    }
?>
 
<div class="form-group">
    {!! Form::label($linkedField, $controller->LL('entity.'.$linkedField), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        <?php 

            $domAttr = [];

			if ($model->{$linkedField})
            {
                $domAttr['disabled'] = 'disabled';
            }

        ?>
        {!! Form::hidden($linkedField, $model->{$linkedField}) !!}
        {!! Form::select($linkedField, \App\Telenok\Core\Model\Object\Type::get(['title', 'id'])->keyBy('id')->transform(function($item) { return $item->translate('title'); })->all(), $model->{$linkedField}, $domAttr) !!}
    </div>
</div> 