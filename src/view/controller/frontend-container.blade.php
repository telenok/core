<div class="table-responsive">

    <table class="table table-bordered table-striped " style="padding: 0; margin: 0;">
		<tbody>
			<tr>
				<td style="vertical-align: top; margin: 0 2px 0 0;">
					<div data-container-id="center" class="frontend-container span" style="padding: 0; margin: 0; min-width: 150px; min-height: 150px;" >
						@if (isset($center))
						@foreach($center as $widget)
						{!! $widget !!}
						@endforeach
						@endif
					</div>
				</td>
			</tr>
		</tbody>
    </table>

</div>