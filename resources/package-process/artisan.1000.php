<?php

    app()->register('App\Vendor\Telenok\Core\CoreServiceProvider');
    app('events')->subscribe('App\Vendor\Telenok\Core\Event\Listener');

    $this->line('Install Telenok CMS', true);

    $this->call('telenok:install');

    $this->line('Seed Telenok CMS', true);

    $this->call('telenok:seed');

    $this->line('Package migrating', true);

    $this->call('migrate', [
        '--path' => 'vendor/telenok/core/src/migrations',
        '--force' => true
    ]);


    $this->line('Package assets publishing');

    $this->call('vendor:publish', [
        '--tag' => ['public'],
        '--provider' => 'App\Vendor\Telenok\Core\CoreServiceProvider',
        '--force' => true
    ]);
