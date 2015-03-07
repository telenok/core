<?php

    $jsUnique = str_random();

?>

<script type="text/javascript">
  
	if (!telenok.hasPresentation('{{$presentationModuleKey}}'))
	{
		var presentationTreeTab{{$uniqueId}} = Clazzzz.extend(
		{
			init: function()
			{
				this.presentationDomId = '';
				this.moduleKey = '';
				this.presentationParam = {};
			},
			setBreadcrumbs: function(param)
			{
				telenok.setBreadcrumbs(param.breadcrumbs);
			},
			getPresentationDomId: function()
			{
				return this.presentationDomId;
			},
			setPageHeader: function()
			{
				jQuery('div.page-header', '#' + this.presentationDomId).html('<h1>' 
						+ this.presentationParam.pageHeader[0] + '<small><i class="fa fa-angle-double-right"></i> ' 
						+ this.presentationParam.pageHeader[1] + '</small></h1>').show();
				return this;
			},
			setParam: function(param)
			{
				this.presentationParam = param; 
                this.presentationDomId = telenok.getPresentationDomId(param.presentation); 
				this.moduleKey = param.key;
				return this;
			},
			getPresentationParam: function(param)
			{
				return this.presentationParam;
			},
			addTab: function(param)
			{
				if (!param.tabKey) return this;

				var id = this.presentationDomId + '-tab-' + param.tabKey;
				var tabs = jQuery('div.telenok-presentation-tabs', '#' + this.presentationDomId);
				var this_ = this;

				if (jQuery('div#' + id, tabs).length)
				{
					jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a[href="#' + id + '"]', tabs).tab('show');
					return this;
				}

				var tabTemplate = "<li><a href='#" + id + "' data-toggle='tab'><i class='green fa fa-home bigger-110'></i>&nbsp;" + param.tabLabel + "&nbsp;<i class='fa fa-times red' style='cursor:pointer;'></i></a></li>";

				var $li = jQuery(tabTemplate);

				jQuery('ul.nav-tabs#nav-tabs-{{$presentation}}', tabs).append($li);

				$li.on('mousedown', function(event)
				{
					if (event.which == 2)
					{
						event.stopPropagation();
						event.preventDefault();
						this_.removePageAttribute();
						jQuery('i.fa.fa-times', this).click(); 
					}
				});

				jQuery('div.tab-content#tab-content-{{$presentation}}', tabs).append("<div class='tab-pane' id='" + id + "'>" + param.tabContent + "</div>");
				jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a:last', tabs).on('shown.bs.tab', function (e) 
					{
						this_.setBreadcrumbs(this_.getPresentationParam()); 
						this_.setPageHeader(); 
					}).tab('show');

				jQuery('a i.fa.fa-times', $li).click(function()
				{
					var tabId = jQuery('a', $li).attr('href');
					jQuery(tabId).remove();
					$li.remove();
					this_.removePageAttribute();
					jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a:last', tabs).tab('show');
				});

				return this;
			},
			addTabByURL: function(param)
			{ 
				var _this = this;
				
				jQuery.ajax(jQuery.extend({}, {
						method: 'get',
						dataType: 'json',
					}, param))
				.done(function(data)
				{
					if (data.exception)
					{
						jQuery.gritter.add({
							title: 'Error',
							text: data.exception,
							class_name: 'gritter-error gritter-light',
							time: 3000,
						});
					}
					else
					{
						_this.addTab({tabKey: data.tabKey, tabLabel: data.tabLabel, tabContent: data.tabContent});

						if(jQuery.isFunction(param.after))
						{
							param.after();
						}
					} 
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

				return this;
			},
			addTree: function() 
			{ 
				if (!this.presentationParam.treeContent) 
				{
					return this;
				}
                
				var key = 'telenok-presentation-' + this.presentationParam.key + '-tree'; 

				jQuery('div.telenok-presentation-tabs', '#' + this.getPresentationDomId()).removeClass('col-xs-12').addClass('col-xs-9');
				jQuery('div.telenok-presentation-tree', '#' + this.getPresentationDomId()).show();

				if (jQuery('#' + key).size()) 
				{
					jQuery('div.telenok-tree', '#' + this.presentationDomId).hide();
					jQuery('#' + key, '#' + this.presentationDomId).show();

					return this;
				}

				jQuery('div.telenok-tree', '#' + this.presentationDomId).hide();

				jQuery('div.telenok-presentation-tree', '#' + this.presentationDomId).append(
						'<div id="' + key + '" class="telenok-tree">'
						+ this.presentationParam.treeContent
						+ '</div>'
					);

				return this;
			},
			addDataTable: function(param)
			{
				var aButtons = [];
				var this_ = this;

				@section('tableListBtnCreate')
					if (param.tableListBtnCreate)
					{
						aButtons.push(param.tableListBtnCreate);
					}
					else 
					{
						aButtons.push({
							"sExtends": "text",
							"sButtonText": "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
							'sButtonClass': 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
							"fnClick": function(nButton, oConfig, oFlash) {
								if (param.btnCreateDisabled || !param.btnCreateUrl) return false;
								else this_.addTabByURL({url : param.btnCreateUrl});
							}
						});
					}
				@show

				@section('tableListBtnRefresh')
					if (param.tableListBtnRefresh)
					{
						aButtons.push(param.tableListBtnRefresh);
					}
					else 
					{
						aButtons.push({
								"sExtends": "text",
								"sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{ $controller->LL('list.btn.refresh') }}",
								'sButtonClass': 'btn-sm',
								"fnClick": function(nButton, oConfig, oFlash) {
									jQuery('#' + param.domId).dataTable().fnReloadAjax();
								}
							});
					}
				@show 

				@section('tableListBtnSelected')
					if (param.tableListBtnSelected)
					{
						aButtons.push(param.tableListBtnSelected);
					}
					else 
					{
						aButtons.push({
							"sExtends": "collection",
							'sButtonClass': 'btn btn-sm btn-light',
							"sButtonText": "<i class='fa fa-check-square-o smaller-90'></i> {{ $controller->LL('list.btn.select') }}",
							"aButtons": [ 
								{
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-pencil-square-o'></i> {{ $controller->LL('btn.edit') }}",
									"fnClick": function(nButton, oConfig, oFlash) 
										{
											if (param.btnListEditUrl)
											{
												this_.addTabByURL({
													url: param.btnListEditUrl, 
													data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize() 
												});
											}
									}
								},
								{
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-lock'></i> {{ $controller->LL('btn.lock') }}",
									"fnClick": function(nButton, oConfig, oFlash) 
                                    {
                                        if (param.btnListLockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).size())
                                        {
                                            jQuery.ajax({
                                                url: param.btnListLockUrl, 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize(),
                                                method: 'get',
                                                dataType: 'json',
                                            }).done(function(data) 
                                            {
                                                if (data.success == 1)
                                                {
                                                    jQuery.gritter.add({
                                                        title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
                                                        text: '{{$controller->LL('notice.saved.thank.you')}}!',
                                                        class_name: 'gritter-success gritter-light',
                                                        time: 3000,
                                                    });
                                                }
                                            }); 
                                        }
									}
								},
								{
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-unlock'></i> {{ $controller->LL('btn.unlock') }}",
									"fnClick": function(nButton, oConfig, oFlash) 
                                    {
                                        if (param.btnListUnlockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).size())
                                        {
                                            jQuery.ajax({
                                                url: param.btnListUnlockUrl, 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize(),
                                                method: 'get',
                                                dataType: 'json'
                                            }).done(function(data) 
                                            {
                                                if (data.success == 1)
                                                {
                                                    jQuery.gritter.add({
                                                        title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
                                                        text: '{{$controller->LL('notice.saved.thank.you')}}!',
                                                        class_name: 'gritter-success gritter-light',
                                                        time: 3000,
                                                    });
                                                }
                                            }); 
                                        }
									}
								},
								{
									"sExtends": "text",
									'sButtonClass':  (param.btnListDeleteDisabled ? ' disabled ' : ''),
									"sButtonText": "<i class='fa fa-trash-o'></i> {{ $controller->LL('btn.delete') }}",
									"fnClick": function(nButton, oConfig, oFlash) {
										if (param.btnListDeleteDisabled || !param.btnListDeleteUrl) return false;
										else 
                                        {
                                            var this_ = this;

											jQuery.ajax({
												url: param.btnListDeleteUrl,
												method: 'post',
												dataType: 'json',
												data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize() 
											}).done(function(data) {
												if (data.success) {
													jQuery('input[name=tableCheckAll\\[\\]]:checked', this_.dom.table).closest("tr").remove();
												}
												else {
													//
												}  
											});
										}
									}
								}
							]
						});
					}
				@show		

				@section('tableListBtnFilter')
					if (param.tableListBtnFilter)
					{
						aButtons.push(param.tableListBtnFilter);
					}
					else 
					{
						aButtons.push({
								"sExtends": "text",
								'sButtonClass': 'btn btn-sm btn-light',
								"sButtonText": "<i class='fa fa-search'></i> {{ $controller->LL('btn.filter') }}",
								"fnClick": function(nButton, oConfig, oFlash) {
									jQuery('div.filter', jQuery(this.dom.table).closest('div.container-table')).toggle();
								}
							});
					}
				@show 				

				param = jQuery.extend({}, {
                    "searchDelay": 1000,
					"multipleSelection": true,
					"aoColumns": [],
					"autoWidth": false,
					"bProcessing": true,
					"bServerSide": param.sAjaxSource ? true : false,
					"bDeferRender": '',
					"bJQueryUI": false,
					"iDisplayLength": {{ $iDisplayLength }},
					"sDom": "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'T><'col-md-6'p>>",
					"oTableTools": {
						@section('tableListBtn')
						"aButtons": aButtons
						@show 				
					},
					"oLanguage": {
						"oPaginate": {
							"sNext": "{{ \Lang::get('core::default.btn.next') }}",
							"sPrevious": "{{ \Lang::get('core::default.btn.prev') }}", 
						},
						"sEmptyTable": "{{ \Lang::get('core::default.table.empty') }}",
						"sSearch": "{{ \Lang::get('core::default.btn.search') }} ",
						"sSearchPlaceholder": "{{ \Lang::get('core::default.table.placeholder.search') }} ",
						"sInfo": "{{ \Lang::get('core::default.table.showed') }}",
						"sInfoEmpty": "{{ \Lang::get('core::default.table.empty.showed') }}",
						"sZeroRecords": "{{ \Lang::get('core::default.table.empty.filtered') }}",
						"sInfoFiltered": "",
					}
				}, param);

				jQuery('#' + param.domId).dataTable(param);

				return this;
			},
			reloadDataTableOnClick: function(param)
			{
				if (jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId).size())
				{
					jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId)
							.dataTable()
							.fnReloadAjax(param.url + (param.data ? '?' + jQuery.param(param.data) : ''));
				}
				return this;
			},
			deleteByURL: function(dom_obj, url)
			{
				jQuery.ajax({
					url: url,
					method: 'post',
					dataType: 'json'
				})
				.done(function(data) 
				{
					if (data.success) 
					{
						jQuery(dom_obj).closest("tr").remove();
					}
				});
			},
			showSkeleton: function()
			{
				var domId = this.presentationDomId;

				if (!jQuery('div#' + domId).size())
				{
					jQuery('.page-content').append(
					  '<div id="' + domId + '" class="telenok-presentation row ui-helper-hidden">'
					+ '	<div>'
					+ '		<div class="page-header position-relativee"></div>'
					+ '		<div>'
					+ '			<div class="telenok-presentation-tree col-xs-3" style="display: none;"></div>'
					+ '			<div class="col-xs-12 telenok-presentation-tabs">'
					+ '				<div class="tabbable">'
					+ '					<ul class="nav nav-tabs" id="nav-tabs-{{$presentation}}"></ul>'
					+ '					<div class="tab-content" id="tab-content-{{$presentation}}"></div>'
					+ '				</div>'
					+ '			</div>'
					+ '		</div>'
					+ '	</div>'
					+ '</div>'
					);
				}

				return this;
			},
			callMe: function(param)
			{ 
				this.setParam(param);  

				param.addSkeleton===false ? '' : this.showSkeleton();
				param.addTree===false  ? '' : this.addTree(); 
                param.addTab===false  ? '' : this.addTabByURL(param); 

				this.setBreadcrumbs(param); 
				this.setPageHeader();

				return this;
			},
			removePageAttribute: function()
			{
				var trees = jQuery('div.telenok-presentation-tree div.telenok-tree', '#' + this.presentationDomId).size();
				
				if (!trees)
				{
					this.removePageHeader();
					this.removeBreadcrumbs();
				}
			},
			removePageHeader: function()
			{
				jQuery('div.page-header', '#' + this.presentationDomId).html("").hide();
			},
			removeBreadcrumbs: function()
			{
				telenok.removeBreadcrumbs();
			}
		});

		@section('addPresentation')
		telenok.addPresentation('{{$presentationModuleKey}}', new presentationTreeTab{{$uniqueId}}());
		@show
	} 

</script> 