<?php

	$jsUniqueId = str_random();

?>

<div class="widget-box transparent light-border ui-sortable-handle" id="widget-box-{{$uniqueId}}">
    <div class="widget-header">
        <h5 class="widget-title smaller">{{$controller->LL('title.widget.variable')}}</h5>

        <div class="widget-toolbar">
            <a data-action="close" href="#">
                <i class="ace-icon fa fa-times"></i>
            </a>
        </div>
    </div>

    <div class="widget-body">
        <div class="widget-main padding-6">

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right">{{$controller->LL('property.choose')}}</label>
				<div class="col-sm-3">
					<div class="input-daterange input-group">
						<div class="input-group">
							<select data-placeholder="{{$controller->LL('notice.choose')}}" id="input-variable{{$uniqueId}}" name="stencil[variable][{{$uniqueId}}][code]">
								<option value=""></option>
								@if ($model)

									@foreach($model->variable()->active()->get() as $variable)

									<option value="{{$variable->code}}" @if ($variable->code == $p->get('code')) selected="selected" @endif>{{$variable->translate('title')}}</option>

									@endforeach
								@endif
							</select> 
						</div>           
						<span class="input-group-addon" style="background: transparent; border: none;">
							<i class="fa fa-arrow-left"></i>
						</span>
						<div class="input-group">
							<input type="text" id="input-template-{{$uniqueId}}" value="{{$p->get('value')}}" placeholder="{{$controller->LL('property.value')}}" name="stencil[variable][{{$uniqueId}}][value]" />
							<span class="input-group-btn">
								<button type="button" id="button-template-{{$uniqueId}}" class="btn btn-sm" data-toggle="modal"><i class="fa fa-align-justify"></i></button>
							</span>
							{!! \Telenok\Core\Workflow\TemplateMarker\TemplateMarkerModal::make()->getMarkerModalContent(
									$uniqueId,
									[
										'fieldId' => 'jQuery("#input-template-' . $uniqueId . '")',
										'buttonId' => 'jQuery("#button-template-' . $uniqueId . '")',
									],
									false,
									[],
									$model?$model->getKey():0) !!}
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<script type="text/javascript">

    jQuery("#input-variable{{$uniqueId}}").chosen({width: "200px"}).trigger("chosen:updated");

</script>
