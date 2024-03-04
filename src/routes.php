<?php

Route::group([

    'namespace' => 'Helious\SeatNotificationsPlus\Http\Controllers',
    'prefix' => 'notifications',
    'middleware' => [
        'web',
        'auth'
    ],
], function()
{

    Route::get('/settings', [
        'uses' => 'NotificationsController@settings',
        'as' => 'seat-notifications::settings',
    ]);

});