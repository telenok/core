<div class="modal-dialog">
    <div class="modal-content">

        <div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{$controller->LL('title')}}</h4>
        </div>

        <div class="modal-body" style="padding: 15px;">
            <div class="widget-main">
                <div class="row">
                    <div class="col-xs-12">
                        <form action="#" onsubmit="return false;" class="form-horizontal" id="model-ajax-{{$uniqueId}}">

                            {!! Form::hidden('sessionProcessId', $sessionProcessId) !!}

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="stencil[title]">{{$controller->LL('property.title')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="stencil[title]" value="{{$property->get('title')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="stencilIdNew">{{$controller->LL('property.stencil.id')}}</label>
                                <div class="col-sm-9">
                                    {!! Form::text('stencilId', $stencilId, ['class' => 'col-sm-8', 'readonly' => 'readonly']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="stencil[bgcolor]">{{$controller->LL('property.bgcolor')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="stencil[bgcolor]" value="{{$property->get('bgcolor')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="stencil[bordercolor]">{{$controller->LL('property.bordercolor')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="stencil[bordercolor]" value="{{$property->get('bordercolor')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="stencil[description]">{{$controller->LL('property.description')}}</label>
                                <div class="col-sm-9">
                                    <textarea style="width: 300px;" name="stencil[description]">{{$property->get('description')}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="stencil[class_method]">{{$controller->LL('property.class_method')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="col-sm-9" name="stencil[class_method]" value="{{$property->get('class_method')}}" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
  
        <div class="modal-footer">

            <div class="center no-margin">

                <button class="btn btn-success" onclick="
                    var $modal = jQuery(this).closest('div.modal');
                    var $form = jQuery('form', $modal);
                    $modal.data('model-data')($form);
                    return false;">
                    {{ $controller->LL('btn.apply') }}
                </button>

                <button class="btn btn-success" data-dismiss="modal" onclick="
                    var $modal = jQuery(this).closest('div.modal');
                    var $form = jQuery('form', $modal);
                    $modal.data('model-data')($form);">
                    {{ $controller->LL('btn.apply.close') }}
                </button>

                <button class="btn btn-danger" data-dismiss="modal">
                    {{ $controller->LL('btn.close') }}
                </button>

            </div>

        </div>

	</div>
</div>
