
<div class="form-group">
    {!!  Form::label("value[{$model->code}][]", $model->translate('title'), [ 'class'=>'col-sm-3 control-label no-padding-right' ]) !!}
    <div class="col-sm-9">
        {!!  Form::select("value[{$model->code}][]",
            \App\Vendor\Telenok\Core\Model\System\Language::orderBy('title')->get()->pluck('title', 'locale'),
            $model->value, [ 'multiple'=>'multiple', 'size'=>10 ] ) !!}
    </div>
</div>