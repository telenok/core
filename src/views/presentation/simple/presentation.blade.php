<?php

    $jsUnique = str_random();

?>

<script type="text/javascript">
  
	if (!telenok.hasPresentation('{{$presentationModuleKey}}'))
	{
		var presentationSimple{{$jsUnique}} = Clazzzz.extend(
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
			addContent: function(param)
			{
				if (!param.content) return this;

				var id = this.presentationDomId + '-tab-' + param.tabKey;
				var content = jQuery('div.telenok-presentation-content', '#' + this.presentationDomId);

				content.append("<div class='tab-pane' id='" + id + "'>" + param.content + "</div>");

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
					+ '	 <div class="page-header position-relativee"></div>'
					+ '	 <div class="col-xs-12 telenok-presentation-content">{!! $content !!}</div>'
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
                param.addContent===false  ? '' : this.addContent(param); 

				this.setBreadcrumbs(param); 
				this.setPageHeader();

				return this;
			},
			removePageAttribute: function()
			{ 
				this.removePageHeader();
				this.removeBreadcrumbs(); 
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
		telenok.addPresentation('{{$presentationModuleKey}}', new presentationSimple{{$jsUnique}}());
		@show
	}
</script> 

