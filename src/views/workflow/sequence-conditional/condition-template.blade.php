<?php

	$jsUniqueId = str_random();

?>
<div class="widget-box transparent light-border ui-sortable-handle" id="widget-box-{{$uniqueId}}">
    <div class="widget-header">
        <h5 class="widget-title smaller">{{$controller->LL('title.widget.condition')}}</h5>

        <div class="widget-toolbar">
            <span class="badge badge-info">{{$controller->LL('badge.and')}}</span>
            <a data-action="close" href="#">
                <i class="ace-icon fa fa-times"></i>
            </a>
        </div>
    </div>

    <div class="widget-body">
        <div class="widget-main padding-6">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('property.type')}}</label>
                <div class="col-sm-9">
                    <select class="chosen-select" id="choose-type-{{$uniqueId}}" data-placeholder="{{$controller->LL('notice.choose')}}" name="stencil[condition][{{$uniqueId}}][type]">
                        <option value="parameter" @if ($p->get('type') == "parameter") selected @endif>{{$controller->LL('property.type.list.parameter')}}</option>
                        <option value="variable" @if ($p->get('type') == "variable") selected @endif>{{$controller->LL('property.type.list.variable')}}</option>
                        <option value="model_field" @if ($p->get('type') == "model_field") selected @endif>{{$controller->LL('property.type.list.model_field')}}</option>
                    </select>
                </div>
            </div>


            <!-- parameter -->
            @if ($model)

            <?php
                $parameterList = $model->parameter()->active()->get();
            ?>

            <div class="form-group conditional-list @if ($p->get('type') != "parameter") display-none @endif parameter-{{$uniqueId}}">
                <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('property.parameter')}}</label>
                <div class="col-sm-9">
                    <select class="chosen-select" data-placeholder="{{$controller->LL('notice.choose')}}" name="stencil[condition][{{$uniqueId}}][parameter]">
                        @foreach($parameterList->all() as $z)
                        <option value="{{$z->code}}" @if ($z->code == $p->get('parameter')) selected="selected" @endif>{{$z->translate('title')}}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <!-- parameter //-->            


            <!-- variable -->
            <div class="form-group conditional-list @if ($p->get('type') != "variable") display-none @endif variable-{{$uniqueId}}">
                <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('property.variable')}}</label>
                <div class="col-sm-9">
                </div>
            </div>
            <!-- variable //-->


            <!-- field of model -->
            <div class="form-group conditional-list @if ($p->get('type') != "model_field") display-none @endif model_field-{{$uniqueId}}">
                <label class="col-sm-3 control-label">{{$controller->LL('property.model.type')}}</label>
                <div class="col-sm-3">
                    <select class="chosen-select-deselect" data-placeholder="{{$controller->LL('notice.choose')}}" id="input-model-type-{{$uniqueId}}" name="stencil[condition][{{$uniqueId}}][model_type]">
                        <option value=""></option>
                        @foreach(\App\Model\Telenok\Object\Type::active()->get() as $type)

                        <option value="{{$type->getKey()}}" @if ($type->getKey() == $p->get('model_type', 0)) selected="selected" @endif>[{{$type->getKey()}}] {{$type->translate('title')}}</option>

                        @endforeach
                    </select> 
                </div>
            </div>

            <div class="form-group conditional-list @if ($p->get('type') != "model_field") display-none @endif model_field-{{$uniqueId}}">
                <label class="col-sm-3 control-label">{{$controller->LL('property.model.field')}}</label>
                <div class="col-sm-3">
                    <select class="chosen-select" data-placeholder="{{$controller->LL('notice.choose')}}" id="input-model-field-{{$uniqueId}}" name="stencil[condition][{{$uniqueId}}][model_field]">
                        <option></option>
                        @foreach(\App\Model\Telenok\Object\Field::active()->get() as $field)

                        <option value="{{$field->code}}" class="field-{{$field->field_object_type}}" @if ($field->getKey() == $p->get('model_field')) selected="selected" @endif>[{{$field->getKey()}}] {{$field->translate('title')}}</option>

                        @endforeach
                    </select> 
                </div>
            </div>
            <!-- field of model //-->


            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('property.case')}}</label>
                <div class="col-sm-9">
                    <select class="chosen-select" data-placeholder="{{$controller->LL('notice.choose')}}" name="stencil[condition][{{$uniqueId}}][case]">
						
						@foreach(['equal', 'not_equal', 'equal_or_less', 'equal_or_more', 'less', 'more'] as $c)
                        <option value="{{$c}}" @if ($c == $p->get('case')) selected="selected" @endif>{{$controller->LL('property.case.list.' . $c)}}</option>
						@endforeach
                    </select>
                </div>
            </div>

            @endif

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('property.value')}}</label>
                <div class="col-sm-9">
                    <input type="text" id="input-template-{{$uniqueId}}" value="{{$p->get('value')}}" name="stencil[condition][{{$uniqueId}}][value]" placeholder="Value" class="col-xs-5 col-sm-5">
					<button id="button-template-{{$uniqueId}}" type="button" class="btn btn-sm" data-toggle="modal"><i class="fa fa-align-justify"></i></button>
                </div>

					{!! \Telenok\Core\Workflow\TemplateMarker\TemplateMarkerModal::make()->getMarkerModalContent(
						$jsUniqueId,
						[
							'fieldId' => 'jQuery("#input-template-' . $uniqueId . '")',
							'buttonId' => 'jQuery("#button-template-' . $uniqueId . '")',
                        ],
						null,
						[],
						$processId) !!}

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    jQuery("select", '#modal-body-{{$uniqueId}}').not('.added-chosen').addClass('added-chosen').chosen({width: "300px"});

    jQuery('#choose-type-{{$uniqueId}}').on('change', function()
    {
        jQuery('div.conditional-list', '#widget-box-{{$uniqueId}}').hide();

        var value = jQuery(this).val();

        jQuery('div.' + value + '-{{$uniqueId}}').show();
    });

    jQuery('#{{'value-' . $jsUniqueId }}')
        .removeClass('form-control')
        .addClass('col-xs-5 col-sm-5').after(jQuery('#{{ 'button-' . $jsUniqueId }}'));

    jQuery("#input-model-type-{{$uniqueId}}").chosen({width: "300px"}).on('change', function()
    {
        jQuery("#input-model-field-{{$uniqueId}} option").hide().removeAttr('selected');
        jQuery("#input-model-field-{{$uniqueId}} option.field-" + jQuery(this).val()).show();

        jQuery("#input-model-field-{{$uniqueId}}").trigger("chosen:updated");
    });

    if ( !{{ intval($p ? $p->get('model_type', 0) : 0) }})
    {
        jQuery("#input-model-field-{{$uniqueId}} option").hide();
    }
    else
    {
        jQuery("#input-model-field-{{$uniqueId}} option").hide();
        jQuery("#input-model-field-{{$uniqueId}} option.field-" + jQuery("#input-model-type-{{$uniqueId}}").val()).show();
    }

    jQuery("#input-model-type-{{$uniqueId}}").trigger("chosen:updated");
    jQuery("#input-model-field-{{$uniqueId}}").trigger("chosen:updated");

</script>
