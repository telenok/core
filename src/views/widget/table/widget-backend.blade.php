<div class="widget-box telenok-widget-box draggable @if (!$widgetOnPage->active) widget-color-red @elseif ($widgetOnPage->isWidgetLink()) widget-color-blue @else widget-color-green @endif" 
	 data-widget-key="{{$key}}" 
	 data-widget-id="{{$id}}"
	 data-widget-buffer-id="0"
	 data-widget-buffer-key="0">
    <div class="widget-header widget-header-small @if (!$widgetOnPage->active) header-color-red @elseif ($widgetOnPage->isWidgetLink()) header-color-blue @else header-color-green @endif">
		<h5 class="widget-title lighter">{{$header}}. {{$widgetOnPage->translate('title')}}</h5>

		<div class="widget-toolbar no-border">
			<a data-action="cut" href="#">
				<i class="fa fa-scissors"></i>
			</a>

			<a data-action="copy" href="#">
				<i class="fa fa-files-o"></i>
			</a>

			<a data-action="copy-link" href="#">
				<i class="fa fa-external-link"></i>
			</a>

			<a data-action="settings" href="#">
				<i class="fa fa-cog"></i>
			</a>

			<a data-action="close" href="#">
				<i class="fa fa-times"></i>
			</a>
		</div>

    </div>
    <div class="widget-body">
		<div class="widget-main no-padding">

			<div class="table-responsive">

				<table class="table table-striped table-bordered" style="padding: 0; margin: 0;">
					<tbody>
						@foreach($rows as $r)
						<tr>
							@foreach($r as $c)
							<td data-container-id="{{$c['container_id']}}" class="frontend-container" 
                                style="vertical-align: top; padding: 2px; margin: 0;">@foreach($c['content'] as $content){!! $content !!}@endforeach</td>
							@endforeach
						</tr>
						@endforeach
					</tbody>
				</table>

			</div>

		</div>
    </div>
</div>