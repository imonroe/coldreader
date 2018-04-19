<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use imonroe\cr_theme_system\Http\Controllers\ColdreaderThemeController;

Route::namespace('imonroe\cr_theme_system\Http\Controllers')->group(
    function () {
        Route::middleware(['web'])->group(function(){
            Route::get('cr_theme/css', 'ColdreaderThemeController@get_css');
            Route::get('cr_theme/json', 'ColdreaderThemeController@get_theme_json');
        });
    }
);