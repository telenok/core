<?php

$listViews = collect();

\Event::fire('telenok.module.setting.' . $controller->getKey() . '.content', [$listViews]);

?>

@foreach($listViews->all() as $v)

{!! $v !!}

@endforeach