
<?php

    $jsUnique = str_random();

?>

<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4 class="row">
			<span class="col-sm-12">
				<i class="ace-icon fa fa-list-ul"></i>
				{{ $controller->LL('title.view') }}
			</span>
		</h4>
	</div>
	<div class="widget-body"> 
		<div class="widget-main form-group field-list">
            
            <div class="form-group">
                <div class="col-sm-12"> 
                    {!!  Form::textarea("template_content", $controller->getTemplateContent(), ['class' => 'form-control']) !!}
                </div>
            </div>
		</div>
	</div>
</div>


<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4 class="row">
			<span class="col-sm-12">
				<i class="ace-icon fa fa-list-ul"></i>
				{{ $controller->LL('title.view') }}
			</span>
		</h4>
	</div>
	<div class="widget-body"> 
		<div class="widget-main form-group field-list">

            <div class="row">
                <div class="col-md-8">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!!  Form::label("structure[menu_type]", $controller->LL('title.menu_type'), array('class' => '')) !!}
                                {!!  Form::select("structure[menu_type]", [
                                        'root' => $controller->LL('title.menu_type.root'),
                                        'list' => $controller->LL('title.menu_type.list'),
                                    ], $model->structure->get('menu_type', 1), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!!  Form::label("structure[object_type]", $controller->LL('title.object_type.type'), array('class' => '')) !!}
                                    <?php

                                        $list = \App\Telenok\Core\Model\Object\Type::where('treeable', 1)->get(['title', 'id'])
                                                    ->transform(function($item) 
                                                        {
                                                            return ['title' => $item->translate('title'), 'id' => $item->id]; 
                                                        })->sortBy('title')->lists('title', 'id');
                                    ?>
                                    {!!  Form::select("structure[object_type]", $list, $model->structure->get('object_type'), [
                                        'class' => 'form-control',
                                        'id' => 'menuTypeList' . $jsUnique,
                                        'onchange' => 'loadTreeList' . $jsUnique . '(this);'
                                    ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!!  Form::label("node_ids", $controller->LL('title.node_ids'), array('class' => '')) !!}
                                {!!  Form::text("structure[node_ids]", $model->structure->get('node_ids', ''), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-sm-12">
                        {!!  Form::label("", $controller->LL('title.object_type.list'), array('class' => '')) !!}

                            {!!  Form::select("", [], $model->structure->get('object_type'), [
                                'class' => 'form-control',
                                'multiple' => 'multiple',
                                'size' => 6,
                                'id' => 'menuTreeList' . $jsUnique
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function loadTreeList{{$jsUnique}}(dom_obj)
                {
                    jQuery.ajax({
                        type: "GET",
                        url: "{!! route('telenok.widget.menu.tree.list') !!}",
                        data: {'typeId': dom_obj.options[dom_obj.selectedIndex].value},
                        dataType: 'json',
                        success: function(data)
                        {
                            var content = '';

                            var f = function(pid)
                            {
                                for(k in data)
                                {
                                    if (data[k].pid == pid)
                                    {
                                        content += '<option>' + 
                                            ((function(string, length) {
                                                return new Array(length).join(string);
                                            })(data[k].depth + 1, "&nbsp;-&nbsp;-&nbsp;")) + '[' + data[k].id + '] ' + data[k].title + '</option>';

                                        f(data[k].id);
                                    }
                                }
                            }

                            for(k in data)
                            {
                                if (data[k].depth == 0)
                                {
                                    content += '<option>' + '[' + data[k].id + '] ' + data[k].title;

                                    f(data[k].id);
                                }
                            }

                            jQuery("#menuTreeList{{$jsUnique}}").html(content);
                        }
                    });
                }

                loadTreeList{{$jsUnique}}(document.getElementById('menuTypeList{{$jsUnique}}'));

            </script>
		</div>
	</div>
</div>