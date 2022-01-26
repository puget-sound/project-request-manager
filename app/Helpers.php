<?php

use App\Projects;
use App\Users;
use App\CheckNotifications;

class Helpers {

	public static function getFullName($username) {
		return ldapGetFullName($username);
	}

	public static function updateLastCheck() {
		$user_id = Helpers::full_authenticate()->id;
		$request['notif_check_user_id'] = $user_id;
		CheckNotifications::where('notif_check_user_id', '=', $user_id)->update($request);
	}

	public static function getNotificationCount() {
		$user_id = Helpers::full_authenticate()->id;
		$last_check = CheckNotifications::where('notif_check_user_id', '=', $user_id)->pluck('updated_at');
		$get_projects = DB::table('requests')
		->join('notifications', 'notifications.notif_project_id', '=', 'requests.id')
		->select(DB::raw('requests.updated_at, requests.id as request_id, requests.request_name, "" as fullname, "P" as flag'))
		->where('notif_user_id', '=', $user_id)
		->whereRaw('requests.updated_at > requests.created_at');
		$comments = DB::table('project_comments')
		->join('notifications', 'notifications.notif_project_id', '=', 'comment_project_id')
		->leftJoin('users', 'comment_user_id', '=', 'users.id')
		->leftJoin('requests', 'comment_project_id', '=', 'requests.id')
		->select(DB::raw('project_comments.updated_at, requests.id as request_id, requests.request_name, users.fullname, "C" as flag'))
		->where('notif_user_id', '=', $user_id);
		$get_notifications = $get_projects->union($comments)
		//->whereRaw("requests.updated_at > $last_check")
		->orderBy('updated_at', 'desc')
		->get();
		return $get_notifications;
	}

	public static function full_authenticate() {
		$client_id = env('OKTA_CLIENT_ID');
		$client_secret = env('OKTA_CLIENT_SECRET');
		$redirect_uri = env('OKTA_REDIRECT_URL');

		$metadata_url = env('OKTA_METADATA_URL');
		$authorization_endpoint = env('OKTA_AUTHORIZATION_ENDPOINT');

		// If there is a username, they are logged in, and we'll show the logged-in view
		if(session('username') !== null) {
  		$username_parts = explode("@", session('username'));
  		$username_short = $username_parts[0];
			$username = $username_short;
			$userinfo = Users::where('username', '=', $username)->where('active', '=', 'Active')->first();
			if ($userinfo == NULL) {
				//return URL::to('www.google.com');
			} else {
				return View::share('userinfo', $userinfo);
			}
		}
		// If there is no username, they are logged out, so show them the login link
		if(session('username') === null)  {
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
  		header('Location: ' . $authorize_url);
		}
	}

	public static function sync_names() {
	  $users = Users::where('active', '!=', 'Inactive')->orderBy('role', 'desc')->orderBy('fullname', 'asc')->get();

	  foreach ($users as $user) {
	     $username = $user->username;
	     $fullname = $user->fullname;
	      $ldapAuthName = 'cn=LDAP Authenticator,cn=Users,dc=pugetsound,dc=edu';
	      $ldappass = 'Sspr609z';
	      $ldapconn = ldap_connect("dm-1.pugetsound.edu dm-3.pugetsound.edu dm-2.pugetsound.edu dm-4.pugetsound.edu")
	          or die("Could not connect to LDAP server.");
	      $ldapbind = ldap_bind($ldapconn, $ldapAuthName, $ldappass);
	      $result = ldap_search($ldapconn, "ou=Accounts,dc=pugetsound,dc=edu", "(samaccountname=$username)") or die ("Error in search query: ".ldap_error($ldapconn));
	      $data = ldap_get_entries($ldapconn, $result);
				if($data["count"] > 0) {
	      	if((trim($data[0]['displayname'][0]) !== trim($fullname)) && trim($data[0]['displayname'][0]) !== ''){
	        	$user->fullname = $data[0]['displayname'][0];
						$user->save();
	      	}
				}
	   ldap_close($ldapconn);
	}
	}
}

function http($url, $params=false) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if($params)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  return json_decode(curl_exec($ch));
}

function ldapGetFullName($username) {
		$ldapAuthName = 'cn=LDAP Authenticator,cn=Users,dc=pugetsound,dc=edu';
		$ldappass = env('LDAP_PASS');
		$ldapconn = ldap_connect("dm-1.pugetsound.edu dm-5.pugetsound.edu dm-2.pugetsound.edu dm-6.pugetsound.edu")
		    or die("Could not connect to LDAP server.");
		$ldapbind = ldap_bind($ldapconn, $ldapAuthName, $ldappass);
		$result = ldap_search($ldapconn, "ou=Accounts,dc=pugetsound,dc=edu", "(samaccountname=$username)") or die ("Error in search query: ".ldap_error($ldapconn));
	    $data = ldap_get_entries($ldapconn, $result);
	    @$id =  $data[0]['cn'][0];
	    ldap_close($ldapconn);
	    return getFullName($id);
	}

	function getFullName($id) {
		$ldapAuthName = 'cn=LDAP Authenticator,cn=Users,dc=pugetsound,dc=edu';
		$ldappass = env('LDAP_PASS');
		$ldapconn = ldap_connect("dm-1.pugetsound.edu dm-5.pugetsound.edu dm-2.pugetsound.edu dm-6.pugetsound.edu")
	    or die("Could not connect to LDAP server.");
		$ldapbind = ldap_bind($ldapconn, $ldapAuthName, $ldappass);
		$result = ldap_search($ldapconn, "ou=Accounts,dc=pugetsound,dc=edu", "(cn=$id)") or die ("Error in search query: ".ldap_error($ldapconn));
		$data = ldap_get_entries($ldapconn, $result);
		@$fullName = $data[0]['displayname'][0];
		if ($fullName != null) {
			return $fullName;
		} else {
			return "err";
		}
	}
