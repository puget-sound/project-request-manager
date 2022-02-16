<?php

namespace App\Http\Controllers;
use App\Users;
use Helpers;

class AuthenticateController extends Controller {
	public function okta_authenticate() {
		$client_id = env('OKTA_CLIENT_ID');
		$client_secret = env('OKTA_CLIENT_SECRET');
		$redirect_uri = env('OKTA_REDIRECT_URL');

		$metadata_url = env('OKTA_METADATA_URL');
		$authorization_endpoint = env('OKTA_AUTHORIZATION_ENDPOINT');

		// Fetch the authorization server metadata which contains a few URLs
		// that we need later, such as the authorization and token endpoints
		$metadata = http($metadata_url);

		if(isset($_GET['code'])) {
			if(session('state') != $_GET['state']) {
				die('Authorization server returned an invalid state parameter');
			}

			if(isset($_GET['error'])) {
				die('Authorization server returned an error: '.htmlspecialchars($_GET['error']));
			}

			$response = http($metadata->token_endpoint, [
				'grant_type' => 'authorization_code',
				'code' => $_GET['code'],
				'redirect_uri' => $redirect_uri,
				'client_id' => $client_id,
				'client_secret' => $client_secret,
			]);

			if(!isset($response->access_token)) {
				die('Error fetching access token');
			}

			$token = http($metadata->introspection_endpoint, [
				'token' => $response->access_token,
				'client_id' => $client_id,
				'client_secret' => $client_secret,
			]);

			if($token->active == 1) {
				session()->put('username', $token->username);
				session()->save();
				return redirect('requests/all');
			}
		}

		// If there is a username, they are logged in, and we'll show the logged-in view
		if(session('username') !== null) {
			$username_parts = explode("@", session('username'));
			$username_short = $username_parts[0];
			$username = $username_short;
			$userinfo = Users::where('username', '=', $username)->where('active', '=', 'Active')->first();
			if ($userinfo == NULL) {
				//return URL::to('www.google.com');
			} else {
				//return View::share('userinfo', $userinfo);
				return redirect('requests/all');
			}
		}
		// If there is no username, they are logged out, so show them the login link
		if(session('username') === null) {
			// Generate a random state parameter for CSRF security
			session()->put('state', bin2hex(openssl_random_pseudo_bytes(5)));
			session()->save();

			// Build the authorization URL by starting with the authorization endpoint
			// and adding a few query string parameters identifying this application
			$authorize_url = $authorization_endpoint.'?'.http_build_query([
				'response_type' => 'code',
				'client_id' => $client_id,
				'redirect_uri' => $redirect_uri,
				'state' => session('state'),
				'scope' => 'openid',
			]);
			//echo '<p>Not logged in</p>';
			//echo '<p><a href="'.$authorize_url.'">Log In</a></p>';
			//header('Location: ' . $authorize_url);
			return redirect()->away($authorize_url);
		}
	}
}
