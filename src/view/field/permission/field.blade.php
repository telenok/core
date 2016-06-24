<?php
    $domAttr = ['class' => 'ace ace-switch ace-switch-3'];
    $disabled = false;
    $value = 1;
    $jsUnique = str_random();
    
    $permissions = [];

    if ($type = $model->fieldObjectType)
    {
        $permissions = $type->permissionType()->get();

        if (!$permissions->count())
        {
            $permissions = \App\Vendor\Telenok\Core\Model\Security\Permission::active()->get();
        }
    }

    $urlListTitle = route($controller->getRouteListTitle(), ['id' => 0]);

?>

<div class="widget-box transparent" data-field-key='{{ $model->code }}'>
    <div class="widget-header widget-header-small">
        <h4>
            <i class="fa fa-list-ul"></i>
            {{ $controller->LL('property.default') }}
        </h4> 
    </div>
    <div class="widget-body"> 
        <div class="widget-main">

            <ul class="nav nav-tabs" id="field-tabs-{{$jsUnique}}-permission">
                @foreach($permissions as $permission) 
                <li><a href="#{{$permission->code . $jsUnique}}" data-toggle="tab">{{$permission->translate('title')}}</a></li>
                @endforeach
            </ul>

            <div class="tab-content" style="overflow: visible;">
                @foreach($permissions as $permission) 
                <div class="tab-pane active" id="{{$permission->code . $jsUnique}}">
                    <div class="controls" style="margin-left: 0;">
                        <select class="chosen" 
                                multiple 
                                data-placeholder="{{$controller->LL('notice.choose')}}" 
                                id="permission-{{$permission->code . $jsUnique}}" 
                                name="permission_default[{{$permission->code}}][]">
                            <?php

                            $sequence = new \App\Vendor\Telenok\Core\Model\Object\Sequence();

                            $selectedIds = $model->permission_default->get($permission->code);
                            
                            $subjects = \App\Vendor\Telenok\Core\Model\Object\Sequence::active()
                                    ->whereIn('id', (array)$selectedIds)
                                    ->get();

                            foreach ($subjects as $subject) {
                                echo "<option value='{$subject->getKey()}' selected='selected'>[{$subject->translate('title_type')}#{$subject->id}] {$subject->translate('title')}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <script type="text/javascript">
                    jQuery('ul#field-tabs-{{$jsUnique}}-permission a:first').tab('show');

                    jQuery("#permission-{{$permission->code . $jsUnique}}").on("chosen:showing_dropdown", function()
                    {
                        telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
                    })
                    .ajaxChosen({
                        keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                        lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                        type: "GET",
                        url: "{!! $urlListTitle !!}",
                        dataType: "json",
                        minTermLength: 1
                    },
                            function (data)
                            {
                                var results = [];

                                jQuery.each(data, function (i, val) {
                                    results.push({value: val.value, text: val.text});
                                });

                                return results;
                            },
                            {
                                width: "100%",
                                no_results_text: "{{ $controller->LL('notice.not-found') }}"
                            });

                </script>
                @endforeach
            </div>

        </div>
    </div>
</div>