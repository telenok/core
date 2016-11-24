<?php

$listViews = collect();

\Event::fire('telenok.module.config.' . $controller->getKey() . '.content', [$listViews]);

?>

@foreach($listViews->all() as $v)

{!! $v !!}

@endforeach