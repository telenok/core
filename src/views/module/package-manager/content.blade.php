<?php

    $jsContentUnique = str_random();

?>


<div class="container-table">

    <div class="table-header">{{ $controller->LL("list.name") }}</div>

    <div class="filter display-none">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title smaller">{{ $controller->LL('table.filter.header') }}</h5>
                <span class="widget-toolbar no-border">
                    <a data-action="collapse" href="#">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </span>
            </div>

            <div class="widget-body">
                <div class="widget-main">
				  
				  @foreach($lists as $l)
				  	<div class="form-group">

					  sssssss {{$l['title']['en']}}
					
					</div>
				  @endforeach
                </div>
            </div>
        </div>
    </div>
</div>