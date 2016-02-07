<?php

    $this->line('Package assets publishing');

    $this->call('vendor:publish', [
        '--tag' => ['public'], 
        '--provider' => 'Telenok\Core\CoreServiceProvider',
        '--force' => true
    ]);
    
    $this->line('Package new classes copy');

    $this->call('vendor:publish', [
        '--tag' => ['resourcesapp'], 
        '--provider' => 'Telenok\News\CoreServiceProvider',
    ]);