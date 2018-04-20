<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use imonroe\cr_network_aspects\Http\Controllers\RSSController;

Route::namespace('imonroe\cr_network_aspects\Http\Controllers')->group(
    function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/rss_get_proxy', 'RSSController@proxy_fetch_feed'); // Proxy for AJAX GET requests.
        });
    }
);