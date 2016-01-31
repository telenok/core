<div class="container-table">

    <div class="table-header">{{$type->translate('title_list')}}</div>
    
    <div class="filter display-none">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title smaller">{{ $controller->LL('table.filter.header') }}</h5>
                <span class="widget-toolbar">
                    
                    <div class="widget-menu">
                        <a href="#" data-action="settings" data-toggle="dropdown">
                            <i class="ace-icon fa fa-bars"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right dropdown-light-blue dropdown-caret dropdown-closer" id="ul-field-filter-{{$uniqueId}}">
                            <li role="presentation" class="dropdown-header">{{ $controller->LL('title.field.filter') }}</li>
                        
                        <?php
                            $userConfig = collect(app('auth')->user()->configuration);
                        ?>
                            
                        @foreach($fieldsFilter->all() as $key => $field) 

                            <li class="{{$key < 2 || $userConfig->get('field-filter.' . $controller->getKey() . '.' . $field->id) ? 'active' : ''}}">
                                <a href="#"
                                    onclick="
                                        jQuery(this).blur();
                                        jQuery('#{{$uniqueId}}-field-filter-{{$field->id}}').toggleClass('hidden');
                                        jQuery(this).closest('li').toggleClass('active');
                                        telenok.updateUserUISetting('field-filter.{{$controller->getKey()}}.{{$field->id}}', jQuery(this).closest('li').hasClass('active') ? 1 : 0);"
                                    >{{ $field->translate('title') }}</a>
                            </li>

                        @endforeach
                            
                        </ul>
                        <script type="text/javascript">
                            jQuery('body').on('click', '#ul-field-filter-{{$uniqueId}} a', function (e) { e.stopPropagation(); e.preventDefault(); });
                        </script>
                    </div>
                    
                </span>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal telenok-object-field-filter" onsubmit="return false;">
						
						<input type="hidden" name="multifield_search" value="1" />
						<input type="hidden" name="typeId" value="{{$type->getKey()}}" />

                        @foreach($fieldsFilter->all() as $key => $field) 
								
							<div class="form-group {{$key < 2 || $userConfig->get('field-filter.' . $controller->getKey() . '.' . $field->id) ? '' : 'hidden'}}" id="{{$uniqueId}}-field-filter-{{$field->id}}">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-1">{{ $field->translate('title') }}</label>
								<div class="col-sm-9">
									{!! app('telenok.config.repository')->getObjectFieldController($field->key)->getFilterContent($field) !!} 
								</div>
							</div> 
						
                        @endforeach
 
						<div class="form-group center">
							<div class="hr hr-8 dotted"></div>
							<button class="btn btn-sm btn-info" onclick="presentationTableFilter{{$uniqueId}}(this);">
								<i class="fa fa-search bigger-110"></i>
								{{ $controller->LL('btn.search') }}
							</button>
							<button class="btn btn-sm" type="reset" onclick="presentationTableFilter{{$uniqueId}}(this, true);">
								<i class="fa fa-eraser bigger-110"></i>
								{{ $controller->LL('btn.clear') }}
							</button>
						</div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}" role="grid"></table>



    <script type="text/javascript">
        (function()
        {
            var presentation = telenok.getPresentation('{{$controller->getPresentationModuleKey()}}');
            var columns = [];

            columns.push({ 
                data : "tableCheckAll", 
                title : 
                    '<label><input type="checkbox" class="ace ace-checkbox-2" name="checkHeader" onclick="var tb=jQuery(\'#' 
                    + presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();' 
                    + 'var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());' 
                    + 'chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));">'
                    + '<span class="lbl">' 
                    + '</span></label>',
                className : "center", 
                width : "20px", 
                defaultContent : '<input type="checkbox" class="ace ace-checkbox-2" name="checkHeader" value=><span class="lbl"></span>', 
                orderable : false
            });

            columns.push({ "data": "tableManageItem", "title": "", "orderable": false }); 

            @foreach($fields as $key => $field)
            columns.push({ 
                data : "{{ $field->code }}", 
                title : "{{ $field->translate('title_list') }}", 
                orderable : @if ($field->allow_sort) true @else false @endif 
            });
			@endforeach

			presentation.addDataTable({
				columns : columns, 
				order : [],
                @if (isset($search))
                search: {search : "{{$search}}"},
                @endif
                    ajax : '{!! $controller->getRouterList(['typeId' => $type->getKey()]) !!}',
                    domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}",
                    btnCreateUrl : '{!! $controller->getRouterCreate(['id' => $type->getKey()]) !!}',
                    btnListEditUrl : '{!! $controller->getRouterListEdit(['id' => $type->getKey()]) !!}',
                    btnListDeleteUrl : '{!! $controller->getRouterListDelete(['id' => $type->getKey()]) !!}',
                    btnListLockUrl : '{!! $controller->getRouterListLock(['id' => $type->getKey()]) !!}',
                    btnListUnlockUrl : '{!! $controller->getRouterListUnlock(['id' => $type->getKey()]) !!}',
                    btnCreateDisabled : '{{ !app('auth')->can('create', "object_type.{$type->code}") }}',
                    btnListDeleteDisabled : '{!!  !app('auth')->can('delete', "object_type.{$type->code}") !!}'
            });
        })();

        function presentationTableFilter{{$uniqueId}}(dom_obj, erase)
        {
            var $form = jQuery(dom_obj).closest('form');

            if (erase)
            {
                jQuery('select option:selected', $form).removeAttr('selected');
                jQuery('.chosen, .chosen-select', $form).trigger('chosen:updated');
                jQuery('input[name="multifield_search"]', $form).val(0);
            }
            else
            {
                jQuery('input[name="multifield_search"]', $form).val(1);
            }
            
            jQuery('#telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}')
                .DataTable().ajax.url('{!! $controller->getRouterList(['typeId' => $type->getKey()]) !!}&' + (erase ? '' : jQuery.param($form.serializeArray()))).load();
        }
    </script>
