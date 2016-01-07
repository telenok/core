
<?php
$modelFiles = \App\Telenok\Core\Model\File\File::take(40)->get();

$i = 0;
?>

@foreach($modelFiles as $k => $file)

<?php $i++; ?>

<li class="col-lg-3 col-sm-6 col-xs-12" style="list-style: none;">
    <div class="thumbnail search-thumbnail">

        @if ($file->upload->isImage())

        <img src="{!! $file->upload->downloadImageLink(300, 300, 
             \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}"
             class="media-object" 
             style="width: 100%; display: block;" 
             data-holder-rendered="true">
        @endif

        <div class="caption">
            <div class="clearfix">
                <a href="#" class="btn-file-choose pull-right btn btn-success btn-xs"
                   data-filename='{{ $file->translate('title') }}'
                   data-src='{!! $file->upload->downloadImageLink(300, 300, 
                   \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}'
                   >Choose</a>
            </div>

            <h3 class="search-title" style="word-wrap: break-word;">
                <span>{{ $file->translate('title') }}</span>
            </h3>
            <p>{{ $file->upload_size }}</p>
        </div>
    </div>
</li>

@if ($i > 0 && $i % 4 == 0)
<li class="clearfix visible-lg-block visible-md-block visible-sm-block visible-xs-block"></li>
@elseif ($i > 0 && $i % 6 == 0)
<li class="clearfix visible-sm-block"></li>
@else
<li class="clearfix visible-sm-block"></li>
@endif


@endforeach
