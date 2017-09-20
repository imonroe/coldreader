<?php
use Illuminate\Http\Request;
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

/*
	Reference for available RESTful routes:
	Route::get($uri, $callback);
	Route::post($uri, $callback);
	Route::put($uri, $callback);
	Route::patch($uri, $callback);
	Route::delete($uri, $callback);
	Route::options($uri, $callback);
*/


Route::get('/theme/theme_css', function(){
	$theme = array();
	$contents = view('theme.theme_css')->with('theme', $theme);
	return response($contents)->header('Content-Type', 'text/css');
});

// Welcome screen and login button.
Route::get('/', function () {
    return redirect()->route('home');
});

// Here we have the routes necessary for authentication.
// we are overriding:
// Auth::routes();
// with the following routes:
// Authentication Routes...

Route::get('login', [
  'as' => 'login',
  'uses' => 'Auth\LoginController@showLoginForm'
  //'uses' => 'Auth\AuthController@redirectToProvider'
]);
Route::post('login', [
  'as' => '',
  'uses' => 'Auth\LoginController@login'
  //'uses' => 'Auth\AuthController@redirectToProvider'
]);
Route::post('logout', [
  'as' => 'logout',
  'uses' => 'Auth\LoginController@logout'
]);

// Password Reset Routes...
Route::post('password/email', [
  'as' => 'password.email',
  'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
  'as' => 'password.request',
  'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/reset', [
  'as' => '',
  'uses' => 'Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
  'as' => 'password.reset',
  'uses' => 'Auth\ResetPasswordController@showResetForm'
]);

// Registration Routes...
Route::get('register', [
  'as' => 'register',
  'uses' => 'Auth\RegisterController@showRegistrationForm'
]);
Route::post('register', [
  'as' => '',
  'uses' => 'Auth\RegisterController@register'
]);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'web']], function () {
	// In this group, we have all the routes that we want to have authentication on, e.g., ALMOST ALL OF THEM.
	Route::get('/home', '\imonroe\crps\Http\Controllers\SubjectController@coldreader_homepage')->name('home');

	// Debuggin routes
	Route::get('/debug', function(){
		//Mail::to( Auth::user()->email )->send(new \App\Mail\UpdateEmail('Routefile test message'));
		//$test_subject = new imonroe\crps\Subject;
		//dd($test_subject);
		echo phpinfo();
	});
	Route::get('/log', function(){
		return view('log', ['log_items' => '']);
	});



	// News Routes
	Route::get('/news', 'RSSController@generate_news_page');
	Route::post('/news/get_feed', 'RSSController@get_feed_via_ajax');

	// Search Routes:
	Route::post('/search/results', 'SearchController@show_search_results');

});  // finished with authenticated routes.