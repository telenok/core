@extends('core::presentation.tree-tab.presentation')

<?php

    $jsPresentationUnique = str_random();

?> 

    @section('tableListBtnCreate')
        aButtons.push({
            "sExtends": "text",
            "sButtonText": "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
            'sButtonClass': 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
            "fnClick": function(nButton, oConfig, oFlash)
            {
                if (param.btnCreateDisabled || !param.btnCreateUrl) return false;
                else
                { 
                    jQuery('#modal-{{$jsPresentationUnique}}').append('body').modal('show').data('model-data', function(id)
                    {
                        var url = "{!! \URL::route("cmf.module.objects-lists.action.param", ['typeId' => '__typeId__']) !!}".replace("__typeId__", id);

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

                                    var url = "{!! \URL::route("cmf.module.objects-lists.create", ['id' => '__id__']) !!}".replace("__id__", id);

                                    this_.addTabByURL({url : url});

                                    jQuery('#modal-{{$jsPresentationUnique}}').modal('hide');
                                }
                            );
                        })
                        .fail(function(jqXHR, textStatus, errorThrown)
                        {
                            jQuery.gritter.add({
                                title: 'Error',
                                text: jqXHR.responseText,
                                class_name: 'gritter-error gritter-light',
                                time: 3000,
                            });
                        });  
                    });
                }
            }
        });
	@stop
    
    
    
    
<div class="modal fade" id="modal-{{$jsPresentationUnique}}">
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

                            $model = new \App\Model\Telenok\Object\Type();

                            $query = $model::withPermission();

							$query->where($model->getTable() . '.code', '!=', 'object_sequence');

                            $query->active()->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$option)
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
    jQuery("#input{{$jsPresentationUnique}}").chosen({ 
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
