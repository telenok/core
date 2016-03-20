<div class="form-group">
    {!!  Form::label($field->code, $field->translate('title'), ['class' => 'col-sm-3 control-label no-padding-right']) !!}
    <div class="col-sm-9">

		<?php

		$acceptedLocales = config('app.locales')->all();
		$defauleLocale = config('app.localeDefault');

		?>

        {!!  Form::select($field->code, \App\Telenok\Core\Model\System\Language::all()
			->filter(function($item) use ($acceptedLocales) { if (in_array($item->locale, $acceptedLocales, true)) return true; })
			->sortBy(function($item) use ($defauleLocale) { if ($item->locale == $defauleLocale) return 0; else return 1; })
			->pluck('title', 'locale'), $model->{$field->code}) !!}
    </div>
</div> 