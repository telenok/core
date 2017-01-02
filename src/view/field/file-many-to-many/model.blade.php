<?php

    $domAttr = ['class' => 'col-md-6', 'disabled' => 'disabled'];
    $method = camel_case($field->code);
    $jsUnique = str_random();
 
    $linkedField = $field->relation_many_to_many_has ? 'relation_many_to_many_has' : 'relation_many_to_many_belong_to';
	
	$disabledCreateLinkedType = false;

	$linkedType = $controller->getLinkedModelType($field);

	if (!app('auth')->can('create', 'object_type.' . $linkedType->code))
	{
		$disabledCreateLinkedType = true;
	}
    
	$disabledCreateFile = false;  
	
	if (!app('auth')->can('create', 'object_type.file'))
	{
		$disabledCreateFile = true;
	}
?>

    <div class="widget-box transparent" data-field-key='{{ $field->code }}'>
        <div class="widget-header widget-header-small">
            <h4>
                <i class="fa fa-list-ul"></i>
                {{ $field->translate('title_list') }}
            </h4> 
        </div>
        <div class="widget-body"> 
            <div class="widget-main field-list">
                <div class="row">
                    <ul class="nav nav-tabs" id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab">
                        <li class="active">
                            <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-current">
                                <i class="fa fa-list bigger-110"></i>
                                {{$controller->LL('current')}}
                            </a>
                        </li>
                        @if ( 
                                ((!$model->exists && $field->allow_create && $permissionCreate) 
                                    || 
                                ($model->exists && $field->allow_update && $permissionUpdate))
                            )
                        <li>
                            <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-addition">
                                <i class="green fa fa-plus bigger-110"></i>
                                {{$controller->LL('addition')}}
                            </a>
                        </li>
                        @endif
					
                        @if (   $field->relation_many_to_many_has && 
                                ((!$model->exists && $field->allow_create && $permissionCreate) || ($model->exists && $field->allow_update && $permissionUpdate))
                                    &&
                                !$disabledCreateFile
                            )
                        <li>
                            <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload">
                                <i class="green fa fa-upload bigger-110"></i>
                                {{$controller->LL('upload')}}
                            </a>
                        </li>
                        @endif

                        @if ($model->exists && $field->allow_update && $permissionUpdate && $field->relation_many_to_many_has)
                        <li>
                            <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-sort">
                                <i class="green fa fa-sort-amount-asc bigger-110"></i>
                                {{$controller->LL('sort')}}
                            </a>
                        </li>
                        @endif

                    </ul>

                    <div class="tab-content">
                        <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-current" class="tab-pane in active">
                            <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getKey()}}-{{$jsUnique}}" role="grid"></table>
                        </div>
                        @if ( 
                                ((!$model->exists && $field->allow_create && $permissionCreate) 
                                    || 
                                ($model->exists && $field->allow_update && $permissionUpdate))
                            )
                        <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-addition" class="tab-pane">
                            <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition" role="grid"></table>
                        </div>
                        @endif

                        @if (   $field->relation_many_to_many_has && 
                                ((!$model->exists && $field->allow_create && $permissionCreate) || ($model->exists && $field->allow_update && $permissionUpdate))
                                    &&
                                !$disabledCreateFile
                            )
                        <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload" class="tab-pane ">

                            <div class="widget-box transparent" data-field-key='{{ $field->code }}'>
                                <div class="widget-header widget-header-small">
                                    <h4>
                                        <i class="fa fa-file-o"></i>
                                        {{ $controller->LL('file.list') }}
                                    </h4> 
                                </div>
                                <div class="widget-body"> 

                                    <div class="widget-main field-list">

                                        <div class="form-horizontal">

                                            <div class="form-group">

                                                {!! Form::label("", $controller->LL('choose.categories'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

                                                <div class="col-sm-5">
                                                    {!! Form::select('category_add[]', 
                                                        \App\Vendor\Telenok\Core\Model\File\FileCategory::active()->get(['title', 'id'])
                                                            ->transform(function($item) { 
                                                                return ['title' => $item->translate('title'), 'id' => $item->id]; 
                                                            })->sortBy('title')->pluck('title', 'id'), 
                                                        $field->file_many_to_many_allow_categories->all(),
                                                        [
                                                            'id' => 'select-file-category-' . $jsUnique, 
                                                            'multiple' => 'multiple'
                                                        ]) !!}
                                                </div>
                                                <script type="text/javascript">
                                                    jQuery("#select-file-category-{{ $jsUnique }}").on("chosen:showing_dropdown", function()
                                                    {
                                                        telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
                                                    }).chosen({
                                                        create_option: false,
                                                        keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                                                        lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                                                        width: '350px',
                                                        search_contains: true
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group">

                                                {!! Form::label("", $controller->LL('permission.read'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

                                                <div class="col-sm-5">
                                                    <select class="chosen" multiple 
                                                        data-placeholder="{{$controller->LL('notice.choose')}}" 
                                                        id="select-file-permission-{{$jsUnique}}" 
                                                        name="permission[read][]">
                                                        <?php

                                                            $sequence = new \App\Vendor\Telenok\Core\Model\Object\Sequence();

                                                            $selectedIds = $field->file_many_to_many_allow_permission->all();

                                                            $subjects = \App\Vendor\Telenok\Core\Model\Object\Sequence::active()
                                                                    ->whereIn('id', (array)$selectedIds)
                                                                    ->get();

                                                            foreach ($subjects as $subject) {
                                                                echo "<option value='{$subject->getKey()}' selected='selected'>[{$subject->translate('title_type')}#{$subject->id}] {$subject->translate('title')}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <script type="text/javascript">
                                                    jQuery("#select-file-permission-{{$jsUnique}}").ajaxChosen({ 
                                                        keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                                                        lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                                                        type: "GET",
                                                        url: "{!! $urlListTitle !!}", 
                                                        dataType: "json",
                                                        minTermLength: 1
                                                    }, 
                                                    function (data) 
                                                    {
                                                        var results = [];

                                                        jQuery.each(data, function (i, val) {
                                                            results.push({ value: val.value, text: val.text });
                                                        });

                                                        return results;
                                                    },
                                                    {
                                                        width: "100%",
                                                        no_results_text: "{{ $controller->LL('notice.not-found') }}"
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group">

                                                {!! Form::label("", $controller->LL('property.file_many_to_many_allow_ext'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

                                                <div class="col-sm-5">
                                                    <div class="help-block">{{$field->file_many_to_many_allow_ext->implode(",")}}</div>
                                                </div>
                                            </div>

                                            <div class="form-group">

                                                {!! Form::label("", $controller->LL('property.file_many_to_many_allow_mime'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

                                                <div class="col-sm-5">
                                                    <div class="help-block">{{$field->file_many_to_many_allow_mime->implode(",")}}</div>
                                                </div>
                                            </div>

                                            <div class="form-group">

                                                {!! Form::label("", $controller->LL('property.file_many_to_many_allow_size'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

                                                <div class="col-sm-5">
                                                    <div class="help-block">{{intval($field->file_many_to_many_allow_size)}} bytes</div>
                                                </div>
                                            </div>

                                            <div class="form-group">

                                                <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone" class="dropzone well"></div>

                                            </div>

                                            <div class="form-actions center">
                                                <button onclick="Dropzone.forElement('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone').processQueue(); 
                                                    return false;" class="btn btn-sm btn-success">
                                                    {{$controller->LL('upload')}}
                                                    <i class="ace-icon fa fa-upload icon-on-right bigger-110"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if ($model->exists && $field->allow_update && $permissionUpdate && $field->relation_many_to_many_has)
                        <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-sort" class="tab-pane">
                            <style>
                                .hover-group {
                                    display: block;
                                    overflow: hidden;
                                }

                                .hover-group {
                                    position: relative;
                                }

                                .hover-group .hover-toggle {
                                    position: absolute;      
                                    bottom: 0;
                                    display: none;
                                }

                                .hover-group:hover .hover-toggle {
                                    display: block;
                                }
                            </style>

                            <div class="row">

                                <ul class="list-inline" id="sortable{{$jsUnique}}">

                                <?php

                                if ($model->{$method}()->exists())
                                {
                                    $items = $model->{$method}()->orderBy('sort')->take(100)->get();
                                ?>    
                                    @foreach($items as $item)
                                    <li class="col-md-2">
                                        <input type="hidden" class="input-sort" name="{{$field->code}}_sort[{{$item->id}}]" value="{{$item->pivot->sort}}" />
                                        <div class="hover-group thumbnail">
                                            <div class="image-wrapper">
                                                <img src="{!! $item->upload->downloadImageLink(200, 200) !!}" title="{{$item->translate('title')}}" class="img-responsive">
                                            </div>
                                            <span>{{\Str::limit($item->translate('title'), 30)}}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                <?php
                                }
                                ?>
                                </ul>
                            </div>

                            <script type="text/javascript">
                                jQuery( "#sortable{{$jsUnique}}" ).sortable({
                                    start: function(event, ui)
                                    {
                                        ui.item.startPos = ui.item.index();
                                    },
                                    update: function(event, ui) 
                                    {
                                        var currentIndex = ui.item.index();

                                        if (ui.item.startPos == currentIndex)
                                        {
                                            return;
                                        }

                                        jQuery.map(jQuery(this).find('li'), function(el)
                                        {
                                            var elSort = jQuery('.input-sort', el).val();

                                            // move right
                                            if (ui.item.startPos < currentIndex 
                                                    && jQuery(el).index() >= ui.item.startPos
                                                    && jQuery(el).index() < currentIndex )
                                            {
                                                jQuery('.input-sort', ui.item).val( Math.max(jQuery('.input-sort', ui.item).val(), elSort) );

                                                jQuery('.input-sort', el).val(parseInt(elSort) - 1);
                                            }
                                            // move left
                                            else if (ui.item.startPos > currentIndex 
                                                    && jQuery(el).index() > currentIndex
                                                    && jQuery(el).index() <= ui.item.startPos)
                                            {
                                                jQuery('.input-sort', ui.item).val( Math.min(jQuery('.input-sort', ui.item).val(), elSort) );

                                                jQuery('.input-sort', el).val(parseInt(elSort) + 1);
                                            }
                                        });
                                    }
                                });
                            </script>
                        </div>
                        @endif
                    </div>
                </div>
            
                <script type="text/javascript">

                (function()
                {
                    jQuery('ul.nav-tabs#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab a:first').tab('show');

                    var presentation = telenok.getPresentation('{{ $controllerParent->getPresentationModuleKey()}}');

                    var columns = []; 
                    var buttons = []; 

                    @foreach($controller->getFormModelTableColumn($field, $model, $jsUnique) as $row)
                    columns.push({!! json_encode($row) !!});
                    @endforeach 

                    buttons.push({
                        text : "<i class='fa fa-refresh smaller-90'></i> {{ $controllerParent->LL('list.btn.refresh') }}",
                        className : 'btn-sm',
                        action : function (e, dt, button, config)
                        {
                            dt.ajax.reload();
                        }
                    });

                    @if ($model->exists && $field->allow_update && $permissionUpdate)
                        buttons.push({
                                text : "<i class='fa fa-trash-o smaller-90'></i> {{ $controllerParent->LL('list.btn.delete.all') }}",
                                className : 'btn-sm btn-danger',
                                action : function (e, dt, button, config)
                                {
                                    removeAllM2M{{$jsUnique}}();
                                }
                            });
                    @endif

                    if (columns.length)
                    {
                        telenok.addDataTable({
                            domId: "telenok-{{$controller->getKey()}}-{{$jsUnique}}",
                            retrieve : true,
                            columns : columns,
                            order: [],
                            pageLength : {{$pageLength}},
                            ajax : '{!! $urlListTable !!}', 
                            buttons : buttons
                        });
                    }

                    buttons = [];

                    @if ( 
                            ((!$model->exists && $field->allow_create && $permissionCreate) 
                                || 
                            ($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
                        )
                    buttons.push({
                        text : "<i class='fa fa-plus smaller-90'></i> {{ $controllerParent->LL('list.btn.create') }}",
                        className : 'btn-success btn-sm',
                        action : function (e, dt, button, config)
                        {
                            createM2M{{$jsUnique}}('{!! $urlWizardCreate !!}');
                        }
                    });
                    @endif	

                    buttons.push({
                        text : "<i class='fa fa-refresh smaller-90'></i> {{ $controllerParent->LL('list.btn.choose') }}",
                        className : 'btn-yellow btn-sm',
                        action : function (e, dt, button, config)
                        {
                            chooseM2M{{$jsUnique}}('{!! $urlWizardChoose !!}');
                        }
                    });

                    if (columns.length)
                    {
                        telenok.addDataTable({
                            domId: "telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition",
                            dom: "<'row'<'col-md-6'B>r>t<'row'<'col-md-6'B>>",
                            retrieve : true,
                            columns : columns,
                            order: [],
                            data : [], 
                            buttons: buttons
                        });
                    }
                })();

                    function addM2M{{$jsUnique}}(val) 
                    {
                        jQuery('<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="'+val+'" />')
                                .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

                        jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='"+val+"']").remove();
                        jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove();
                    }

                    function removeM2M{{$jsUnique}}(val) 
                    {
                        jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="'+val+'" />')
                                .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

                        jQuery("input.{{$field->code}}_add_{{$jsUnique}}[value='"+val+"']").remove();
                        jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove(); 
                    }

                    function removeAllM2M{{$jsUnique}}() 
                    {
                        jQuery("input.{{$field->code}}_delete_{{$jsUnique}}").remove();

                        var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

                        jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="*" />')
                                .insertBefore($table);

                        jQuery('tbody tr', $table).addClass('line-through red');
                        jQuery('tbody tr button.trash-it i', $table).removeClass('fa fa-trash-o').addClass('fa fa-power-off');
                        jQuery('tbody tr button.trash-it', $table).removeClass('btn-danger').addClass('btn-success');
                    }

                    function createM2M{{$jsUnique}}(url) 
                    {
                        jQuery.ajax({
                            url: url,
                            method: 'get',
                            dataType: 'json'
                        }).done(function(data) {

                            if (!jQuery('#modal-{{$jsUnique}}').size())
                            {
                                jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                            }

                            var $modal = jQuery('#modal-{{$jsUnique}}');

                            $modal.data('model-data', function(data)
                            {
                                data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteM2MAddition{{$jsUnique}}(this); return false;">'
                                    + '<i class="fa fa-trash-o"></i></button>';

                                var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                                var a = $dt.fnAddData(data, true);
                                var oSettings = $dt.fnSettings();
                                var nTr = oSettings.aoData[ a[0] ].nTr;

                                addM2M{{$jsUnique}}(data.id);
                            });

                            $modal.html(data.tabContent);

                            $modal.modal('show').on('hidden', function() 
                            { 
                                jQuery(this).empty(); 
                            });
                        });
                    }

                    function editTableRow{{$field->code}}{{$uniqueId}}(obj, url) 
                    {
                        jQuery.ajax({
                            url: url,
                            method: 'get',
                            dataType: 'json'
                        }).done(function(data) {

                            if (!jQuery('#modal-{{$jsUnique}}').size())
                            {
                                jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                            }

                            var $modal = jQuery('#modal-{{$jsUnique}}');

                            $modal.data('model-data', function(data)
                            {  
                                var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
                                var $dt = $table.dataTable();
                                var $tr = jQuery(obj).closest('tr');
                                    $dt.fnUpdate({title: data.title}, $tr[0], 1);
                            });

                            $modal.html(data.tabContent);

                            $modal.modal('show').on('hidden', function() 
                            { 
                                jQuery(this).empty(); 
                            });
                        });
                    }

                    function deleteTableRow{{$field->code}}{{$uniqueId}}(obj) 
                    {
                        var $dt = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}").dataTable();
                        var $tr = jQuery(obj).closest("tr");

                        var data = $dt.fnGetData($tr[0]);

                        $tr.toggleClass('line-through red');
                        jQuery('button.trash-it i', $tr).toggleClass('fa fa-trash-o').toggleClass('fa fa-power-off');
                        jQuery('button.trash-it', $tr).toggleClass('btn-danger').toggleClass('btn-success');

                        removeM2M{{$jsUnique}}(data.id);
                    }

                    function deleteM2MAddition{{$jsUnique}}(obj) 
                    {
                        var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                        var $tr = jQuery(obj).closest("tr");

                        var data = $dt.fnGetData($tr[0]);
                        var rownum = $dt.fnGetPosition($tr[0]);
                            $dt.fnDeleteRow(rownum);

                        removeM2M{{$jsUnique}}(data.id);
                    } 

                    function chooseM2M{{$jsUnique}}(url) 
                    {
                        jQuery.ajax({
                            url: url,
                            method: 'get',
                            dataType: 'json'
                        }).done(function(data) {

                            if (!jQuery('#modal-{{$jsUnique}}').size())
                            {
                                jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                            }

                            var $modal = jQuery('#modal-{{$jsUnique}}');

                            $modal.data('model-data', function(data)
                            {	
                                data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteM2MAddition{{$jsUnique}}(this); return false;">'
                                    + '<i class="fa fa-trash-o"></i></button>';

                                var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                                var a = $dt.fnAddData(data, true);
                                var oSettings = $dt.fnSettings();
                                var nTr = oSettings.aoData[ a[0] ].nTr;

                                addM2M{{$jsUnique}}(data.id);
                            });

                            $modal.html(data.tabContent);

                            $modal.modal('show').on('hidden', function() 
                            { 
                                jQuery(this).empty(); 
                            });
                        });
                    }

                    try
                    {
                        jQuery("div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone").dropzone({
                                url: "{!! route($controller->getRouteUpload()) !!}",
                                paramName: "upload", // The name that will be used to transfer the file
                                maxFilesize: {{floatval($field->file_many_to_many_allow_size / 1000000)}}, // MB
                                addRemoveLinks : true,
                                dictDefaultMessage :
                                    '<span class="bigger-150 bolder"><i class="fa fa-caret-right red"></i> Drop files</span> to upload \
                                    <span class="smaller-80 grey">(or click)</span> <br /> \
                                    <i class="upload-icon fa fa-cloud-upload blue fa fa-3x"></i>',
                                dictResponseError: 'Error while uploading file!',
                                autoProcessQueue: false,
                                parallelUploads: 4,
                                uploadMultiple: true,
                                acceptedFiles: '{{$field->file_many_to_many_allow_mime->merge(
                                        $field->file_many_to_many_allow_ext->transform(function($item){ return '.' . $item; }))->implode(",")}}',
                                headers: {
                                    'X-CSRF-Token': jQuery('meta[name="csrf-token"]').attr('content')
                                },
                                //change the previewTemplate to use Bootstrap progress bars
                                previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-success progress-striped active\"><span class=\"bar\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
                            });

                        Dropzone.forElement('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone')
                            .on("success", function(file, id) {
                                jQuery('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload').append(
                                    '<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="' + id + '" />'
                                );
                            });

                        Dropzone.forElement('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone').on("sending", function(file, xhr, formData) {
                                formData.append("title", file.name);

                                var arr = jQuery('#select-file-category-{{$jsUnique}}').val();

                                if (arr instanceof Array)
                                {
                                    arr.forEach(function(item, i, arr) 
                                    {
                                        formData.append("category_add[]", item);
                                    });
                                }

                                var arr = jQuery('#select-file-permission-{{$jsUnique}}').val();

                                if (arr instanceof Array)
                                {
                                    arr.forEach(function(item, i, arr) 
                                    {
                                        formData.append("permission[read][]", item);
                                    });
                                }
                            });
                    }
                    catch(e) {}

                </script>
 
            </div>
        </div>
    </div>