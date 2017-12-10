@extends('core::presentation.tree-tab.presentation')

<?php

    $jsPresentationUnique = str_random();

?> 

    @section('tableListBtnCreate')
        buttons.push({
            text : "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
            className : 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
            action : function (e, dt, button, config) {
                if (param.btnCreateDisabled) return false;
                else
                {
                    if (!jQuery('#modal-choose-type-{{$jsPresentationUnique}}').data('model-data'))
                    {
                        jQuery('#modal-choose-type-{{$jsPresentationUnique}}').data('model-data', function(id)
                        {
                            var url = "{!! route("telenok.module.objects-lists.action.param", ['typeId' => '__typeId__']) !!}".replace("__typeId__", id);

                            jQuery.ajax({
                                method: 'get',
                                dataType: 'json',
                                url: url
                            })
                            .done(function(data)
                            {
                                telenok.addModule(
                                    data.key,
                                    url,
                                    function(moduleKey)
                                    {
                                        param = telenok.getModule(data.key);

                                        param.addTree = false;
                                        param.addTab = false;

                                        telenok.setModuleParam(data.key, param);
                                        telenok.processModuleContent(data.key);

                                        var url = "{!! route("telenok.module.objects-lists.create", ['id' => '__id__']) !!}".replace("__id__", id);

                                        jQuery('#modal-choose-type-{{$jsPresentationUnique}}').modal('hide');

                                        return this_.addTabByURL({url : url});
                                    }
                                );
                            });
                        });
                    }

                    jQuery('#modal-choose-type-{{$jsPresentationUnique}}').append('body').modal('show');
                }
            }
        });
	@stop
    
    
    
    
<div class="modal fade" id="modal-choose-type-{{$jsPresentationUnique}}">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header table-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4>Please, choose type of new object</h4>
            </div>

            <div class="modal-body">
                <div class="widget-main">

                    <select class="chosen-type{{$jsPresentationUnique}}" data-placeholder="{{$controller->LL('notice.choose')}}" id="input{{$jsPresentationUnique}}" name="id">

                        <?php

                            $model = new \App\Vendor\Telenok\Core\Model\Object\Type();

                            $query = $model::withPermission();

							$query->where($model->getTable() . '.code', '!=', 'object_sequence');

                            $query->active()->distinct()->get()->each(function($item) use (&$option)
                            {
                                $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
                            });

                        ?>

                        {!! implode('', $option) !!}

                    </select> 

                </div>
            </div>

            <div class="modal-footer">

                <div class="center no-margin">
                    <button class="btn btn-success" onclick="openTab{{$jsPresentationUnique}}(this);">
                        {{ $controller->LL('btn.continue') }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery("#input{{$jsPresentationUnique}}").on("chosen:showing_dropdown", function()
    {
        telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
    }).chosen({ 
        keepTypingMsg: "{{$controller->LL('notice.typing')}}",
        lookingForMsg: "{{$controller->LL('notice.looking-for')}}",
        type: "GET",
        dataType: "json",
        inherit_select_classes: 1,
        minTermLength: 1,
        width: "200px",
        no_results_text: "{{$controller->LL('notice.not-found')}}" 
    });


    function openTab{{$jsPresentationUnique}}(obj) 
    {
        jQuery(obj).closest('div.modal').data('model-data')(jQuery('#input{{$jsPresentationUnique}}').val()); 
    }
</script>
