
<?php

	$jsUnique = str_random();

?>

<div class="row">
	<div class="col-sm-6">
		<h3>{{ array_get($packageInfo, 'title.en') }}</h3>


		<blockquote>
			<small>
				{{ array_get($packageInfo, 'description.en') }}
			</small>
		</blockquote>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<button class="btn btn-app btn-success btn-sm" onclick='installPackage{{$jsUnique}}(); return false;'>
			<i class="ace-icon fa fa-gavel bigger-200"></i>
			Install
		</button>
	</div>
</div>

<div class="row">
	<div class="col-xs-12"> 
		<ul class="list-unstyled spaced2" id="status-{{$jsUnique}}">
		</ul>
	</div>
</div>

<script type="text/javascript">

	var interval{{$jsUnique}} = 0;
	var n{{$jsUnique}} = 0;

	function installPackage{{$jsUnique}}()
	{
		interval{{$jsUnique}} = setInterval(function()
		{
			jQuery.ajax({
					type: "get",
					url: "{!! route('telenok.module.installer-manager.install-package.status', ['packageId' => array_get($packageInfo, 'key'), 'versionId' => 'latest']) !!}",
					dataType: 'json'
				})
				.done(function(data)
				{
					if (n{{$jsUnique}} < Object.keys(data).length)
					{
						n{{$jsUnique}} = data.length;
						
						jQuery('#status-{{$jsUnique}}').empty();
						
						jQuery.each(data, function(index, value)
						{
							jQuery('#status-{{$jsUnique}}').append(
								'<li>'
								+ '<i class="ace-icon fa fa-circle green"></i>'
								+ value
								+ '</li>'
							);
						});
						
						jQuery('#status-{{$jsUnique}} li:last i').removeClass('fa-circle').addClass('fa-cog fa-spin');
					}
				})
				.fail(function(data)
				{
					jQuery('#status-{{$jsUnique}}').append(
						'<li>'
						+ '<i class="ace-icon fa fa-circle red"></i>'
						+ (data instanceof Array || data instanceof Object ? data.exception : data)
						+ '</li>'
					);
				});
			
		}, 2000); 

		jQuery.ajax({
				type: "POST",
				url: "{!! route('telenok.module.installer-manager.install-package', ['packageId' => array_get($packageInfo, 'key'), 'versionId' => 'latest']) !!}",
				dataType: 'json'
			})
			.always(function()
			{
				clearInterval(interval{{$jsUnique}});
			})
			.done(function(data)
			{
				if (data.finished)
				{
					jQuery('#status-{{$jsUnique}} li i.fa-spin').removeClass('fa-cog fa-spin').addClass('fa-circle');
					
					jQuery('#status-{{$jsUnique}}').append(
						'<li>'
						+ '<i class="ace-icon fa fa-circle green"></i>'
						+ data.finished
						+ '</li>'
					);
				}
			})
			.fail(function(data)
			{
				jQuery('#status-{{$jsUnique}}').append(
					'<li>'
					+ '<i class="ace-icon fa fa-circle red"></i> Fail'
					+ (data instanceof Array || data instanceof Object ? data.exception : data)
					+ '</li>'
				);
			});

		return false;
	}

</script>