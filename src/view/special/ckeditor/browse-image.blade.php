
<div class="modal-dialog" id="modal-dialog-{{$jsUnique}}" style="width: 1210px;" role="document">
    <div class="modal-content">

        <div class="modal-header table-header">
            <button data-dismiss="modal" class="close" type="button">×</button>
            <h4>{{ $controller->LL('wizard.file.header') }}</h4>
        </div>
        <div class="modal-body" style="padding: 15px; position: relative;">

            <div class="row">
                <div class="col-sm-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#tab-storage-list-{{$jsUnique}}">
                                    <i class="green ace-icon fa fa-home bigger-120"></i>
                                    {{$controller->LL('tab.title.file-list')}}
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#tab-model-list-{{$jsUnique}}">
                                    <i class="green ace-icon fa fa-home bigger-120"></i>
                                    {{$controller->LL('tab.title.model-list')}}
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="tab-storage-list-{{$jsUnique}}" class="tab-pane fade in active">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="select-directory-{{$jsUnique}}">{{$controller->LL('select.directory')}}</label>
                                            <select class="form-control" id="select-directory-{{$jsUnique}}">

                                                <?php

                                                    $collection = collect($controller->storageDirectoryList())->transform(function($item) use ($controller)
                                                    {
                                                        return trim(str_replace($controller->getRootDirectory(), '', $item), '\\/');
                                                    });

                                                ?>

                                                <option value="" selected="selected">/</option>

                                                @foreach($collection as $c)
                                                <option value="{{$c}}">{{$c}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="storage-search-file-{{$jsUnique}}">{{$controller->LL('search.filename')}}</label>
                                            <input type="text" value="" id="storage-search-file-{{$jsUnique}}" class="form-control" placeholder="{{$controller->LL('btn.search')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label" for="actions-storage-{{$jsUnique}}" role='group'>{{$controller->LL('actions')}}</label>
                                        <div class="btn-group">
                                            <button type="button" id="actions-storage-{{$jsUnique}}" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Choose action <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="javascript:void(0);" data-toggle="modal" data-target="#create-directory-{{$jsUnique}}">{{$controller->LL('create.directory')}}</a></li>
                                                <li><a href="javascript:void(0);" id="dropdown-upload-directory-{{$jsUnique}}">{{$controller->LL('upload.files')}}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <ul class="row" id='storage-list-{{$jsUnique}}' style="padding: 0 0 0 0; margin: 15px 0 0 0;">
                                </ul>

                            </div>

                            <div id="tab-model-list-{{$jsUnique}}" class="tab-pane fade">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="model-search-file-{{$jsUnique}}">{{$controller->LL('search.filename')}}</label>
                                            <input type="text" value="" id="model-search-file-{{$jsUnique}}" class="form-control" placeholder="{{$controller->LL('btn.search')}}"/>
                                        </div>
                                    </div>
                                </div>

                                <ul class="row" id='model-list-{{$jsUnique}}' style="padding: 0 0 0 0; margin: 15px 0 0 0;">
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <a class="btn" data-dismiss="modal">{{ $controller->LL('btn.close') }}</a>
        </div>
    </div>
</div>

<script>

function getStorageList{{$jsUnique}}()
{
    jQuery.ajax({
        url: "{!! route('telenok.ckeditor.storage.list') !!}",
        dataType: 'html',
        data: {
            directory: jQuery('#select-directory-{{$jsUnique}}').val(),
            allow_new: 1,
            allow_blob: 1,
            file_type: 'image',
            jsUnique: "{{$jsUnique}}"
        }
    })
    .done(function(data)
    {
        jQuery("#storage-list-{{$jsUnique}}").html(data);
    });
}
 

function getModelList{{$jsUnique}}(name)
{
    jQuery.ajax({
        url: "{!! route('telenok.ckeditor.model.list') !!}",
        dataType: 'html',
        data: {
            allow_new: 1,
            allow_blob: 1,
            name: name ? name : '',
            file_type: 'image',
            jsUnique: "{{$jsUnique}}"
        }
    })
    .done(function(data)
    {
        jQuery("#model-list-{{$jsUnique}}").html(data);
    });
}

getStorageList{{$jsUnique}}();
getModelList{{$jsUnique}}();

</script>