	@if ($field->code == 'process')
		<div id='modal-business-{{$uniqueId}}' class="modal fade" role="dialog" aria-labelledby="label" style="overflow-y: hidden;">
			<div class="modal-dialog" style="width:100%;">
				<div class="modal-content">

					<div class="modal-header table-header" style="margin-bottom: 0;">
						<button class="close" type="button" onclick="applyDiagram{{$uniqueId}}(true, true, true); return false;">×</button>
						<h4>Business process editor</h4>
					</div>
					<div class="modal-body" style="max-height: none; text-align: center; padding: 0;"></div>
					<div class="modal-footer">
						<a href="#" class="btn btn-success" onclick="applyDiagram{{$uniqueId}}(); return false;">Apply</a>
						<a href="#" class="btn btn-success" onclick="applyDiagram{{$uniqueId}}(false, true); return false;">Apply and close</a>
						<a href="#" class="btn btn-danger" onclick="applyDiagram{{$uniqueId}}(true, true, true); return false;">Close</a>
					</div>

				</div>
			</div>
		</div>

		<!-- Process field -->

		{!! Form::hidden('process', $model->{$field->code}) !!}

		<div class="form-group">
			{!! Form::label('process', $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
			<div class="col-sm-3">
				<button type="button" class="btn" onclick="showProcessModal{{$uniqueId}}(); return false;">
					{{ $controller->LL('btn.open-process-editor') }}
				</button>
			</div>
		</div>

		<script type="text/javascript">

			var diagramData{{$uniqueId}} = {!! $model->{$field->code}->has('diagram') ? "'" . json_encode($model->{$field->code}->get('diagram')) . "'" : 'false' !!};

			function applyDiagram{{$uniqueId}}(clear, hide, clearOnly)
			{
				if (window.frames['frame-process-{{$uniqueId}}'] && window.frames['frame-process-{{$uniqueId}}'].oryxEditor)
				{
					jQuery.ajax({
						url: '{!! URL::route("cmf.workflow.apply-diagram") !!}',
						method: 'post',
						dataType: 'json',
						data: {
							'diagram': window.frames['frame-process-{{$uniqueId}}'].oryxEditor.getSerializedJSON(),
							'clear': clear ? 1 : 0,
							'clearOnly': clearOnly ? 1 : 0,
							'sessionProcessId': "{{ $sessionProcessId }}",
							'_token': "{{ csrf_token() }}",
							'id': '{{$model->getKey()}}'
						}
					}).done(function(data) 
					{
						if (!clearOnly)
						{
							jQuery('input[name="process"]', '#model-ajax-{{$uniqueId}}').val(JSON.stringify(data, null, 2));

							diagramData{{$uniqueId}} = JSON.stringify(data.diagram, null, 2);
						}

						jQuery('div.modal-body', this).empty();

						if (hide)
						{
							jQuery('#modal-business-{{$uniqueId}}').modal('hide');
						}  
					});
				}
			}

			function showProcessModal{{$uniqueId}}()
			{
				var modal = jQuery('#modal-business-{{$uniqueId}}').appendTo(document.body);
					modal
						.modal('show') 
						.on('hide.bs.modal', function() {
                            jQuery('div.modal-body', this).html("");
                        }); 

				if (!jQuery("#frame-process-{{$uniqueId}}").size())
				{
					jQuery('div.modal-body', modal)
						.html(  '<iframe name="frame-process-{{$uniqueId}}" id="frame-process-{{$uniqueId}}" ' +
								' style="width: 100%; border: none;"' + 
								' src="{!! URL::route("cmf.module.workflow-process.diagram.show", ['processId' => intval($model->getKey()), 'sessionProcessId' => $sessionProcessId]) !!}" />')
                }

				var frame = jQuery('#frame-process-{{$uniqueId}}');
					frame.css({
						'height' : jQuery(window).height() - 200
					});

				frame.load(function()
                {
                    this.contentWindow.focus();
                    
					window.frames['frame-process-{{$uniqueId}}'].importJSONFromTop = function() 
					{ 
						return diagramData{{$uniqueId}};
					}
				});
			}
		</script>
	@else
		{!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}
	@endif