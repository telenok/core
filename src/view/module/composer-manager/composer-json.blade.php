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
		else if (button_type=='save' || button_type=='save.close')
		{	
			var $content = jQuery('pre#{{$jsContentUnique}} code').text();
			jQuery("input#content-{{$jsContentUnique}}").val($content);
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
				jQuery.gritter.add({
					title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
					text: '{{$controller->LL('notice.saved.thank.you')}}!',
					class_name: 'gritter-success gritter-light',
					time: 3000,
				});
				
				$el.closest('div.container-model-{{$uniqueId}}').html(data.tabContent); 
			}
		@stop 

@stop


@section('form')

	@parent 

	@section('formField') 

	<input type="hidden" name="content" value="" id="content-{{$jsContentUnique}}" />

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
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            {{$controller->LL('btn.save')}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.save.close')}}
        </button>
        <button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'update');">
            {{$controller->LL('btn.update.packages')}}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>
	@stop

@stop
 
