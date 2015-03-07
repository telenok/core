 

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{$controller->LL('title')}}</h4>
		</div>
			
        <div class="modal-body" style="max-height: none; padding: 15px;">
            <div class="widget-main">
                <div class="row">
                    <div class="col-xs-12">
                        <form action="#" onsubmit="return false;" class="form-horizontal">

                            {!! Form::hidden('sessionProcessId', $sessionProcessId) !!}

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[title]">{{$controller->LL('property.title')}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[title]" value="{{$property->get('title')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencilIdNew">{{$controller->LL('property.stencil.id')}}</label>
                                <div class="col-sm-8">
                                    {!! Form::text('stencilId', $stencilId, ['class' => 'col-sm-8', 'readonly' => 'readonly']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[bgcolor]">{{$controller->LL('property.bgcolor')}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[bgcolor]" value="{{$property->get('bgcolor')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[bordercolor]">{{$controller->LL('property.bordercolor')}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[bordercolor]" value="{{$property->get('bordercolor')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[description]">{{$controller->LL('property.description')}}</label>
                                <div class="col-sm-3">
                                    <textarea style="width: 300px;" name="stencil[description]">{{$property->get('description')}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[model_type]">{{$controller->LL('property.model.type')}} <span class="red">*</span></label>
                                <div class="col-sm-3">
                                    <select class="chosen-select-deselect" data-placeholder="{{$controller->LL('notice.choose')}}" id="input-model-type{{$uniqueId}}" name="stencil[model_type]">
                                        <option value=""></option>
                                        @foreach(\App\Model\Telenok\Object\Type::active()->get() as $type)
                                        
                                        <option value="{{$type->getKey()}}" @if ($type->getKey() == $property->get('model_type', 0)) selected="selected" @endif>[{{$type->getKey()}}] {{$type->translate('title')}}</option>
                                        
                                        @endforeach
                                    </select> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[field_list]">{{$controller->LL('property.field.list')}} <span class="red">*</span></label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" multiple data-placeholder="{{$controller->LL('notice.choose')}}" id="input-field-list{{$uniqueId}}" name="stencil[field_list][]">
                                        @foreach(\App\Model\Telenok\Object\Field::active()->get() as $field)
                                        
                                        <option value="{{$field->getKey()}}" class="field-{{$field->field_object_type}}" @if (in_array($field->getKey(), $property->get('field_list', []), true)) selected="selected" @endif>[{{$field->getKey()}}] {{$field->translate('title')}}</option>
                                        
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery("#input-model-type{{$uniqueId}}").chosen({width: "300px"}).on('change', function()
            {
                jQuery("#input-field-list{{$uniqueId}} option").hide().removeAttr('selected');
                jQuery("#input-field-list{{$uniqueId}} option.field-" + jQuery(this).val()).show();
                
                jQuery("#input-field-list{{$uniqueId}}").trigger("chosen:updated");
            });

            jQuery("#input-field-list{{$uniqueId}}").chosen({width: "300px"});

            if ( !{{ $property->get('model_type', 0) }})
            {
                jQuery("#input-field-list{{$uniqueId}} option").hide();
            }
            else
            {
                jQuery("#input-field-list{{$uniqueId}} option").hide();
                jQuery("#input-field-list{{$uniqueId}} option.field-" + jQuery("#input-model-type{{$uniqueId}}").val()).show();
            }

            jQuery("#input-model-type{{$uniqueId}}").trigger("chosen:updated");
            jQuery("#input-field-list{{$uniqueId}}").trigger("chosen:updated");

        </script>

        <div class="modal-footer">

            <div class="center no-margin">

                <button class="btn btn-success" onclick="
                    var $modal = jQuery(this).closest('div.modal');
                    var $form = jQuery('form', $modal);
                    $modal.data('model-data')($form);
                    return false;">
                    {{ $controller->LL('btn.apply') }}
                </button>

                <button class="btn btn-success" data-dismiss="modal" onclick="
                    var $modal = jQuery(this).closest('div.modal');
                    var $form = jQuery('form', $modal);
                    $modal.data('model-data')($form);">
                    {{ $controller->LL('btn.apply.close') }}
                </button>

                <button class="btn btn-danger" data-dismiss="modal">
                    {{ $controller->LL('btn.close') }}
                </button>

            </div>

        </div>

	</div>
</div>
