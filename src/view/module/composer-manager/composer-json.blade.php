@extends('core::layout.model')

	<?php

		$jsContentUnique = str_random();

	?>

@section('script')

	@parent 
	
		@section('buttonType')
	
		if (button_type=='close')
		{	
			var divId = $el.closest('div.tab-pane').attr('id');

			jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();

			return;
		}
		else if (button_type=='save' || button_type=='save.close' || button_type=='save.update')
		{
			if (button_type=='save.update')
			{
				jQuery("#alert-update-composer-{{$jsContentUnique}}").show();

				jQuery.gritter.add({
					title: '{{ $controller->LL('composer.update.start') }}',
					text: '{{ $controller->LL('notice.composer.update.start') }}!',
					class_name: 'gritter-success gritter-light',
					time: 3000,
				});
/*
				if (!jQuery('#modal-cropper-{{$jsContentUnique}}').size())
				{
					jQuery('body').append('<div id="modal-cropper-{{$jsContentUnique}}" class="modal" role="dialog" aria-labelledby="label"></div>');
				}

				jQuery('#modal-cropper-{{$jsContentUnique}}').html(data).modal('show')
*/
			}

			var $content = jQuery('pre#{{$jsContentUnique}} code').text();
			jQuery("input#content-{{$jsContentUnique}}").val($content);
            
            if (button_type=='save.update')
            {
                jQuery("input#action-{{$jsContentUnique}}").val("save.update");
            }
            else
            {
                jQuery("input#action-{{$jsContentUnique}}").val("save");
            }
		}
		@stop
		
		@section('ajaxDone')
			if (button_type == 'save.close')
			{
				var divId = $el.closest('div.tab-pane').attr('id');

				jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();
			}
			else if (button_type=='save')
			{
				$el.closest('div.container-model-{{$uniqueId}}').html(data.tabContent);
			}
		@stop


		@section('ajaxFail')
			@parent
			jQuery("#alert-update-composer-{{$jsContentUnique}}").hide();
		@stop

@stop


@section('form')

	<div id="alert-update-composer-{{$jsContentUnique}}" class="alert alert-block alert-success" style="display: none;">
		<button data-dismiss="alert" class="close" type="button">
			<i class="fa fa-times"></i>
		</button>
		<p>
			<strong>
				<i class="fa fa-check"></i>
				{{ $controller->LL('composer.update.start') }}!
			</strong>
			{{ $controller->LL('notice.composer.update.start') }}
		</p>
	</div>

	@parent 

	@section('formField') 

	<input type="hidden" name="content" value="" id="content-{{$jsContentUnique}}" />
	<input type="hidden" name="action" value="" id="action-{{$jsContentUnique}}" />

	<div class="form-group">
		<div class="col-sm-12">
			<div>
			  <pre id="{{$jsContentUnique}}"><code class="json" style="min-height: 400px;" contenteditable="true">{{$content}}</code></pre>
			</div>
			<script>
				jQuery('pre#{{$jsContentUnique}} code').each(function(i, block) 
				{
                    hljs.highlightBlock(block);
				});
			</script>
		</div>
	</div>
	@stop

	@section('formBtn')
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');" autofocus="autofocus">
            {{$controller->LL('btn.save')}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.save.close')}}
        </button>
        <button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.update');">
            {{$controller->LL('btn.update.packages')}}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>
	@stop

@stop
 
