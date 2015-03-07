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
                            $userConfig = \Illuminate\Support\Collection::make(app('auth')->user()->configuration); 
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
									{!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFilterContent($field) !!} 
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

        var presentation = telenok.getPresentation('{{$controller->getPresentationModuleKey()}}');
		
        var aoColumns = []; 

                aoColumns.push({ "mData": "tableCheckAll", "sTitle": '<label><input type="checkbox" name="checkHeader" class="ace ace-switch ace-switch-6" onclick="var tb=jQuery(\'#' + 
                            presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));"><span class="lbl"></span></label>', 
							"mDataProp": null, "sClass": "center", "sWidth": "20px", 
							"sDefaultContent": '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]"><span class="lbl"></span></label>', 
							"bSortable": false});
                @foreach($fields as $key => $field)
                        aoColumns.push({ "mData": "{{ $field->code }}", "sTitle": "{{ $field->translate('title_list') }}", "mDataProp": null, "bSortable": @if ($field->allow_sort) true @else false @endif });
                    @if ( ($key==1 && $fields->count() > 1) || ($key==0 && $fields->count() < 2) )
                        aoColumns.push({ "mData": "tableManageItem", "sTitle": "{{ $controller->LL('action') }}", "bSortable": false });
                    @endif
                @endforeach

                presentation.addDataTable({
                    aoColumns : aoColumns,
					aaSorting: [],
                    sAjaxSource : '{!! $controller->getRouterList(['typeId' => $type->getKey()]) !!}',
                    domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}",
                    btnCreateUrl : '{!! $controller->getRouterCreate(['id' => $type->getKey()]) !!}',
                    btnCreateTitle : '{{ $controller->LL('list.btn.create') }}',
                    btnListEditUrl : '{!! $controller->getRouterListEdit(['id' => $type->getKey()]) !!}',
                    btnListDeleteUrl : '{!! $controller->getRouterListDelete(['id' => $type->getKey()]) !!}',
                    btnListLockUrl : '{!! $controller->getRouterListLock(['id' => $type->getKey()]) !!}',
                    btnListUnlockUrl : '{!! $controller->getRouterListUnlock(['id' => $type->getKey()]) !!}',
                    btnCreateDisabled : '{{ !\Auth::can('create', "object_type.{$type->code}") }}',
                    btnListDeleteDisabled : '{{ !\Auth::can('delete', "object_type.{$type->code}") }}'
                });

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
                .dataTable()
                .fnReloadAjax('{!! URL::route("cmf.module.{$controller->getKey()}.list") !!}?typeId={{$type->getKey()}}&' + (erase ? '' : jQuery.param($form.serializeArray())));
        }
    </script>
</div>