<div class="widget-box telenok-widget-box draggable @if (!$widgetOnPage->active) widget-color-red @elseif ($widgetOnPage->isWidgetLink()) widget-color-blue @else widget-color-green @endif" 
	 data-widget-key="{{$key}}" 
	 data-widget-id="{{$id}}" 
	 data-widget-buffer-id="0"
	 data-widget-buffer-key="0">
    <div class="widget-header widget-header-small">
		<h5 class="widget-title lighter">{{$header}}. {{$widgetOnPage->translate('title')}}</h5>

		<div class="widget-toolbar no-border">
			<a data-action="cut" href="#">
				<i class="ace-icon fa fa-scissors"></i>
			</a>

			<a data-action="copy" href="#">
				<i class="ace-icon fa fa-files-o"></i>
			</a>

			<a data-action="copy-link" href="#">
				<i class="ace-icon fa fa-external-link"></i>
			</a>

			<a data-action="settings" href="#">
				<i class="ace-icon fa fa-cog"></i>
			</a>

            <a data-action="close" href="#">
				<i class="ace-icon fa fa-times"></i>
			</a>
		</div>
    </div>
</div>