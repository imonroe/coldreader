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


Route::get('/', 'WelcomeController@show');

Route::get('/bug-report', function(){
  return view('static_pages.bug_report');
});

Route::get('/contact', function(){
  return view('static_pages.contact');
});
Route::post('/contact', function(){
  // process the contact form.
  return view('static_pages.contact', ['message' => 'Your submission has been processed.']);
});

Route::get('/faq', function(){
  return view('static_pages.faq');
});

Route::get('/privacy', function(){
  return view('static_pages.privacy');
});

Route::get('/tos', function(){
  return view('static_pages.tos');
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

  // Profile routes
  Route::put('/settings/profile/details', 'ProfileDetailsController@update');

});  // finished with authenticated routes.


