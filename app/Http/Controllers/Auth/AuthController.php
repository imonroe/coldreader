<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Log;

use Auth;
use Socialite;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Google_Client;
use Google_Service_People;

class AuthController extends Controller{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(){

		if (Auth::check()){
		//if (false){
			// The user is logged in...
			$user = Socialite::driver('google')->user();
        	$user_data = array(
            	'token' => $user->token,
            	'refreshToken' => $user->refreshToken,
            	'expiresIn' => $user->expiresIn,
            	'id' => $user->getId(),
            	'nickname' => $user->getNickname(),
            	'name' => $user->getName(),
            	'email' => $user->getEmail(),
            	'avatar' => $user->getAvatar(),
        	);
			$builtin_user = Auth::user();
			$builtin_user->google_token = serialize($user_data);
			$builtin_user->save();
			session( [ 'user_data' => $user_data ] );
	    	return redirect()->intended('home');

		} else {

    	$scopes_array = array(
    		'https://www.googleapis.com/auth/drive',
    		'https://www.googleapis.com/auth/calendar',
    		'https://www.googleapis.com/auth/tasks',
    		'https://www.googleapis.com/auth/userinfo.email',
    		'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.google.com/m8/feeds/',
    	);
        	return Socialite::driver('google')
							->scopes($scopes_array)
							->with(["access_type" => "offline", "prompt" => "consent select_account"])
							->redirect();
		}
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request){

		// First, we use Socialite to get user information from Google
		// Of course, we can use other providers if we preferred, but we need google tokens anyway
		// and they support 2FA, so we'll use that.

        $user = Socialite::driver('google')->user();
        $user_data = array(
            'token' => $user->token,
            'refreshToken' => $user->refreshToken,
            'expiresIn' => $user->expiresIn,
            'id' => $user->getId(),
            'nickname' => $user->getNickname(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
        );

		// Now that we know who Google says they are, we need to check to make sure there is a local user with that email address.
		// Note, Socialite User objects are not authenticatable, so instead, we'll 
		// check to make sure a qualified local account exists, and then authenticate the \App\User model
		// which IS authenticable.

		$builtin_user = \App\User::where('email', $user_data['email'])->first();
		if (!empty($builtin_user->password)){
			Auth::login($builtin_user, true);
			$builtin_user->google_token = serialize($user_data);
			$builtin_user->save();
			session( [ 'user_data' => $user_data ] );
	    	return redirect()->intended('home');
		} else {
			$log_message = 'Unauthorized login attempted with the following information: '.var_export($user_data, true);
			Log::info($log_message);
			return response()->view('errors.403', ['message' => 'That account is not authorized to use this system. Your information has been logged.']);
		}
    }
}
