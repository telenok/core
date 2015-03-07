<?php

    $jsUnique = str_random();

?>

<div class="input-group col-sm-8">

    <ul class="nav nav-tabs" id="field-tabs-{{$jsUnique}}-permission">
        @foreach($permissions as $permission) 
        <li><a href="#{{$permission->code . $jsUnique}}" data-toggle="tab">{{$permission->translate('title')}}</a></li>
        @endforeach
    </ul>

    <div class="tab-content" style="overflow: visible;">
        @foreach($permissions as $permission) 
        <div class="tab-pane active" id="{{$permission->code . $jsUnique}}">
            <div class="controls" style="margin-left: 0;">
                <select class="chosen" multiple data-placeholder="{{$controller->LL('notice.choose')}}" id="permission-{{$permission->code . $jsUnique}}" name="filter[{{$field->code}}][{{$permission->id}}][]">
                </select>
            </div>
        </div>

        <script type="text/javascript">
            jQuery('ul#field-tabs-{{$jsUnique}}-permission a:first').tab('show'); 

            jQuery("#permission-{{$permission->code . $jsUnique}}").ajaxChosen({ 
                keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                type: "GET",
                url: "{!! URL::route("cmf.field.permission.list.title") !!}", 
                dataType: "json",
                minTermLength: 1
            }, 
            function (data) 
            {
                var results = [];

                jQuery.each(data, function (i, val) {
                    results.push({ value: val.value, text: val.text });
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
