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
		Cas::authenticate();
		$username = Cas::getCurrentUser();
		$userinfo = Users::where('username', '=', $username)->where('active', '=', 'Active')->first();
		if ($userinfo == NULL) {
			//return URL::to('www.google.com');
		} else {
			return View::share('userinfo', $userinfo);
		}
	}
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
		@$fullName = $data[0]['description'][0];
		if ($fullName != null) {
			return $fullName;
		} else {
			return "err";
		}
	}
