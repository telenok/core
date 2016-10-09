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
			showTree: function() 
			{
				if (!this.presentationParam.treeContent) 
				{
					return this;
				}
				
				var key = 'telenok-presentation-' + this.presentationParam.key + '-tree'; 
				jQuery('div.telenok-presentation-tabs', '#' + this.getPresentationDomId()).removeClass('col-xs-12').addClass('col-xs-9');
				jQuery('div.telenok-presentation-tree', '#' + this.getPresentationDomId()).show();
			},
			hideTree: function() 
			{
				if (!this.presentationParam.treeContent) 
				{
					return this;
				}
				
				var key = 'telenok-presentation-' + this.presentationParam.key + '-tree'; 
				jQuery('div.telenok-presentation-tabs', '#' + this.getPresentationDomId()).removeClass('col-xs-9').addClass('col-xs-12');
				jQuery('div.telenok-presentation-tree', '#' + this.getPresentationDomId()).hide();
			},
			addTree: function() 
			{ 
				if (!this.presentationParam.treeContent) 
				{
					return this;
				}
                
				this.showTree();

				var key = 'telenok-presentation-' + this.presentationParam.key + '-tree'; 

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
				var buttons = param.buttons || [];
				var this_ = this;

				@section('tableListBtnCreate')
					if (param.tableListBtnCreate)
					{
						buttons.push(param.tableListBtnCreate);
					}
					else if (param.tableListBtnCreate !== false)
					{
						buttons.push({
							text : "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
							className : 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
                            action : function (e, dt, button, config)
                            {
								if (param.btnCreateDisabled || !param.btnCreateUrl) return false;
								else this_.addTabByURL({url : param.btnCreateUrl});
							}
						});
					}
				@show

				@section('tableListBtnRefresh')
					if (param.tableListBtnRefresh)
					{
						buttons.push(param.tableListBtnRefresh);
					}
					else if (param.tableListBtnRefresh !== false)
					{
						buttons.push({
                            text : "<i class='fa fa-refresh smaller-90'></i> {{ $controller->LL('list.btn.refresh') }}",
                            className : 'btn-sm',
                            action : function (e, dt, button, config)
                            {
                                dt.ajax.reload();
                            }
                        });
					}
				@show 

				@section('tableListBtnSelected')
					if (param.tableListBtnSelected)
					{
						buttons.push(param.tableListBtnSelected);
					}
					else if (param.tableListBtnSelected !== false)
					{
						buttons.push({
							extend: 'collection',
							className : 'btn btn-sm btn-light',
							text : "<i class='fa fa-check-square-o smaller-90'></i> {{ $controller->LL('list.btn.select') }}",
							buttons : [ 
								{
									text: "<i class='fa fa-pencil-square-o'></i> {{ $controller->LL('btn.edit') }}",
                                    action : function (e, dt, button, config)
                                    {
                                        if (param.btnListEditUrl)
                                        {
                                            this_.addTabByURL({
                                                url: param.btnListEditUrl, 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize() 
                                            });
                                        }
									}
								},
								{
									text : "<i class='fa fa-lock'></i> {{ $controller->LL('btn.lock') }}",
                                    action : function (e, dt, button, config)
                                    {
                                        if (param.btnListLockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).size())
                                        {
                                            jQuery.ajax({
                                                url: param.btnListLockUrl, 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize(),
                                                method: 'post',
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
									text : "<i class='fa fa-unlock'></i> {{ $controller->LL('btn.unlock') }}",
                                    action : function (e, dt, button, config)
                                    {
                                        if (param.btnListUnlockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).size())
                                        {
                                            jQuery.ajax({
                                                url: param.btnListUnlockUrl, 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize(),
                                                method: 'post',
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
									className :  (param.btnListDeleteDisabled ? ' disabled ' : ''),
									text : "<i class='fa fa-trash-o'></i> {{ $controller->LL('btn.delete') }}",
                                    action : function (e, dt, button, config)
                                    {
										if (param.btnListDeleteDisabled || !param.btnListDeleteUrl) return false;
										else 
                                        {
                                            var this_ = this;

											jQuery.ajax({
												url: param.btnListDeleteUrl,
												method: 'post',
												dataType: 'json',
												data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize() 
											}).done(function(data) {
												if (data.success) {
													jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).closest("tr").remove();
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
						buttons.push(param.tableListBtnFilter);
					}
					else if (param.tableListBtnFilter !== false)
					{
						buttons.push({
								className : 'btn btn-sm btn-light',
								text : "<i class='fa fa-search'></i> {{ $controller->LL('btn.filter') }}",
                                action : function (e, dt, button, config)
                                {
									jQuery('div.filter', dt.table().body().closest('div.container-table')).toggle();
								}
							});
					}
				@show

				param = jQuery.extend({}, {
                    searchDelay : 1000,
					columns : [],
					autoWidth : false,
					processing : true,
					serverSide : param.ajax ? true : false,
					deferRender : true,
					JQueryUI : false,
					pageLength : {{ $pageLength }},
                    dom : "<'row'<'col-md-9'B><'col-md-3'f>r>t<'row'<'col-md-9'B><'col-md-3'p>>",
                    @section('tableListBtn')
                    buttons : buttons,
                    @show 				
					language : {
						paginate : {
							next : "{{ trans('core::default.btn.next') }}",
							previous : "{{ trans('core::default.btn.prev') }}", 
						},
						emptyTable : "{{ trans('core::default.table.empty') }}",
						search : "{{ trans('core::default.btn.search') }} ",
						searchPlaceholder : "{{ trans('core::default.table.placeholder.search') }} ",
						info : "{{ trans('core::default.table.showed') }}",
						infoEmpty : "{{ trans('core::default.table.empty.showed') }}",
						zeroRecords : "{{ trans('core::default.table.empty.filtered') }}",
						infoFiltered : "",
					}
				}, param);

				jQuery('#' + param.domId).DataTable(param);

				return this;
			},
			reloadDataTableOnClick: function(param)
			{
				if (jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId).size())
				{
					jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId)
                        .DataTable().ajax.url(param.url + (param.data ? '?' + jQuery.param(param.data) : '')).load();
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
				jQuery('div.page-header', '#' + this.presentationDomId).empty().hide();
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