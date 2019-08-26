<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Route::redirect('/', '/home', 301);

Route::get('/', function(){

	$users = \App\User::all();
    $user_count = $users->count();
    if ($users->isEmpty()){
        return redirect('register');
    }
	return redirect()->route('home');
	
});

Route::get('/logout', function(){
	Auth::logout();
	return view('auth.login');
});

Route::get('/help', function(){
  return view('static_pages.help');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'web']], function () {
	// In this group, we have all the routes that we want to have authentication on, e.g., ALMOST ALL OF THEM.
	Route::get('/home', '\imonroe\crps\Http\Controllers\SubjectController@coldreader_homepage')->name('home');

	Route::view('/preferences', 'settings.profile.update-application-settings');

});  // finished with authenticated routes.


