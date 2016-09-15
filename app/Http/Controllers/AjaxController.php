<?php

namespace App\Http\Controllers;

use App\Projects;
use App\Notifications;
use App\CheckNotifications;
use App\Comments;
use App\Users;
use App\UserMappings;
use Carbon\Carbon;
use Html;
use Helpers;
use Request;
use DB;
use Response;

class AjaxController extends Controller {

	public function flag_project($project_id)
	{
		$user_id = Helpers::full_authenticate()->id;
		$status_code = 200;
		$exists = Notifications::where('notif_user_id', '=', $user_id)->where('notif_project_id', '=', $project_id)->first();
		if ($exists == NULL) {
			Notifications::insert(['notif_user_id' => $user_id, 'notif_project_id' => $project_id]);
			$response = ['color' => 'black'];
		} else {
			Notifications::where('notif_user_id', '=', $user_id)
			->where('notif_project_id', '=', $project_id)
			->delete();
			$response = ['color' => '#ccc'];
		}
		return Response::json($response, $status_code);
	}

	public function getNewNotificationCount() {
		$status_code = 200;
		$user_id = Helpers::full_authenticate()->id;
		$last_check = CheckNotifications::where('notif_check_user_id', '=', $user_id)->pluck('updated_at');
		if($last_check == '') {
			$response = ['notif_num' => '0'];
		}
		else {
		$project_count = Projects::join('notifications', 'requests.id', '=', 'notifications.notif_project_id')
		->select('requests.*')
		->where('notif_user_id', '=', $user_id)
		->where('requests.updated_at', '>', $last_check)
		->get();
		$numProjects = count($project_count);
		$comment_count = Comments::join('notifications', 'project_comments.comment_project_id', '=', 'notifications.notif_project_id')
		->select('project_comments.*')
		->where('notifications.notif_user_id', '=', $user_id)
		->where('project_comments.updated_at', '>', $last_check)
		->get();
		$numComments = count($comment_count);
		$response = ['notif_num' => $numProjects + $numComments];
		}
		return Response::json($response, $status_code);
	}

	public function grant_edit_for_owner($owner_id, $user_id)
	{
		$status_code = 200;
		$getMapRow = UserMappings::where('owner_id', '=', $owner_id)->where('user_id', '=', $user_id)->first();

		if ($getMapRow->edit == 0) {
			$getMapRow->edit = 1;
			$getMapRow->save();
			$response = ['inputSetting' => 'true'];
		} else if ($getMapRow->edit == 1) {
			$getMapRow->edit = 0;
			$getMapRow->save();
			$response = ['inputSetting' => 'false'];
		}

		return Response::json($response, $status_code);
	}

	public function getCurrentRequestStatus($request_id) {
		$status_code = 200;
		$statusXlat = "";
		$reqStatus = Projects::where('id', '=', $request_id)->pluck('status');
		if ($reqStatus == "") {
			$statusXlat = "Unknown";
		} else if ($reqStatus == "7") {
			$statusXlat = "New";
		} else if ($reqStatus == "0") {
			$statusXlat = "Needs Review";
		} else if ($reqStatus == "1") {
			$statusXlat = "Pending";
		} else if ($reqStatus == "2") {
			$statusXlat = "Ready to Schedule";
		} else if ($reqStatus == "3") {
			$statusXlat = "Scheduled / In Progress";
		} else if ($reqStatus == "4") {
			$statusXlat = "Refer to Oracle";
		} else if ($reqStatus == "5") {
			$statusXlat = "Deferred";
		} else if ($reqStatus == "0") {
			$statusXlat = "Completed";
		}
		$response = ['statusCode' => $reqStatus, 'statusXlat' => $statusXlat];
		return Response::json($response, $status_code);
	}

}
