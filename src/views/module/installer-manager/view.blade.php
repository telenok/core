
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

<script type="text/javascript">

	function installPackage{{$jsUnique}}()
	{
		$.ajax({
				type: "POST",
				url: "{!! route('cmf.module.installer-manager.install-package', ['packageId' => array_get($packageInfo, 'key'), 'versionId' => 'latest']) !!}",
				dataType: 'json',
				success: function(data)
				{
				}
			});
		
		return false;
	}

</script>