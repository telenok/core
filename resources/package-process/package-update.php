<?php

    $this->line('Package assets publishing');

    $this->call('vendor:publish', [
        '--tag' => ['public'], 
        '--provider' => 'Telenok\Core\CoreServiceProvider',
        '--force' => true
    ]);