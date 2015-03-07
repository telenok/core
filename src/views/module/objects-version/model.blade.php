@extends('core::presentation.tree-tab-object.model')

@section('form')
	@parent
	
	@section('formBtn')
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            {{ $controller->LL('btn.restore') }}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{ $controller->LL('btn.close') }}
        </button>
    </div>
	@stop
	
@stop

<script type="text/javascript">
	jQuery("#model-ajax-{{$uniqueId}} :input").not('button').not(':hidden').attr("disabled", "disabled");
</script>
 