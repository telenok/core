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
						+ this.presentationParam.pageHeader[1] + '</small></h1>');
				return this;
			},
			setParam: function(param)
			{
				this.presentationParam = param;
				this.presentationDomId = telenok.getPresentationDomId(param.presentation);
				this.moduleKey = param.key;
				return this;
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
				param.addTab===false  ? '' : this.addTabByURL(param); 

				this.setBreadcrumbs(param); 
				this.setPageHeader();

				return this;
			}
		});
	
		@section('addPresentation')
		telenok.addPresentation('{{$presentationModuleKey}}', new presentationTreeTab{{$uniqueId}}());
		@show
	}
	
</script> 
 