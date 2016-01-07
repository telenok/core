
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
                                <a data-toggle="tab" href="#public-file-list">
                                    <i class="green ace-icon fa fa-home bigger-120"></i>
                                    Public file list
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#database-file-list">
                                    <i class="green ace-icon fa fa-home bigger-120"></i>
                                    Database file list
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="public-file-list-{{$jsUnique}}" class="tab-pane fade in active">

                            </div>

                            <div id="database-file-list-{{$jsUnique}}" class="tab-pane fade">

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
            allow_blob: 0,
            jsUnique: "{{$jsUnique}}"
        }
    })
    .done(function(data)
    {
        jQuery("#public-file-list-{{$jsUnique}}").html(data);
    });
}

getStorageList{{$jsUnique}}();

</script>