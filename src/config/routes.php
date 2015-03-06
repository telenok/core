<?php

 
if (!\Request::is('telenok', 'telenok/*'))
{
    return;
}

//\Route::filter('csrf', 'Telenok\Core\Filter\Router\Controller@csrf');
//\Route::filter('auth', 'Telenok\Core\Filter\Router\Backend\Controller@auth');
//\Route::filter('access-module', 'Telenok\Core\Filter\Router\Backend\Controller@accessModule');

\Route::when('/*', ['middleware' => 'csrf'], ['post']);

\Route::get('telenok/login', array('as' => 'backend.login', 'uses' => "Telenok\Core\Controller\Backend\Controller@updateBackendUISetting"));
