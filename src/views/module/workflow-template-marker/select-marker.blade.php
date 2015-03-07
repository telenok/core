<?php

    $selectTemplateMarkerUnique = str_random();

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$selectTemplateMarkerUnique}}">
                <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                &nbsp;{{$controller->LL('name')}}
            </a>
        </h4>
    </div>

    <div class="panel-collapse collapse in" id="{{$selectTemplateMarkerUnique}}">
        <div class="panel-body">
            <select size="{{ ( ($q = count($item)) > 10 ? 10 : $q ) }}">
                @foreach($item as $k => $b)

                <option class="chooseMeOnClick" value="{{ '{=' . strtoupper($controller->getKey() . ':' . $k) . '}' }}">{{$b}} - {{ '{=' . strtoupper($controller->getKey() . ':' . $k) . '}' }}</option>

                @endforeach
            </select>
        </div>
    </div>
</div> 