<?php

namespace App\Http\Controllers;
use App\Projects;
use App\Owners;
use App\CheckNotifications;
use App\Notifications;
use App\UserMappings;
use App\Users;
use Session;
use Request;
use App\Sprints;
use DB;
use Helpers;
use App\Http\Requests\ProjectsRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Requests\CommentsRequest;
use App\Http\Requests\NotificationsRequest;
use App\Comments;
use Cas;
use Carbon\Carbon;


class ProjectsController extends Controller {
	//Loads Open Projects into projects view throught projects.blade.php

	public function projects_by_owner($owner_id) {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$owner = Owners::where('id', '=', $owner_id)->first();
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->where('project_owner', '=', $owner_id)
		->whereNotIn('status', [6, 5])
		->orderBy('priority')
		->orderBy('order')
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		//$projects = Projects::where('id', '=', 1)->orderBy('priority')->orderBy('order')->get();
		return view('content.projects', ['projects' => $projects, 'owner' => $owner, 'user' => $userdata, 'notifications' => $notifications]);
	}

	public function my_open_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$owners = Owners::join('user_mappings', 'user_mappings.owner_id', '=', 'project_owners.id')
		->where('user_mappings.user_id', '=', $user_id)
		->lists('owner_id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->whereNotIn('status', [6, 5])
		->whereIn('project_owner', $owners)
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		//$projects = Projects::where('id', '=', 1)->orderBy('priority')->orderBy('order')->get();
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function my_completed_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$owners = Owners::join('user_mappings', 'user_mappings.owner_id', '=', 'project_owners.id')
		->where('user_mappings.user_id', '=', $user_id)
		->lists('owner_id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->where('status', '=', '6')
		->whereIn('project_owner', $owners)
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		//$projects = Projects::where('id', '=', 1)->orderBy('priority')->orderBy('order')->get();
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function my_deferred_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$owners = Owners::join('user_mappings', 'user_mappings.owner_id', '=', 'project_owners.id')
		->where('user_mappings.user_id', '=', $user_id)
		->lists('owner_id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->where('status', '=', '5')
		->whereIn('project_owner', $owners)
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		//$projects = Projects::where('id', '=', 1)->orderBy('priority')->orderBy('order')->get();
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function my_closed_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$owners = Owners::join('user_mappings', 'user_mappings.owner_id', '=', 'project_owners.id')
		->where('user_mappings.user_id', '=', $user_id)
		->lists('owner_id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->whereIn('project_owner', $owners)
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		//$projects = Projects::where('id', '=', 1)->orderBy('priority')->orderBy('order')->get();
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function all_open_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->whereNotIn('status', [6, 5])
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function all_completed_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->where('status', '=', '6')
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function all_deferred_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$edit_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->where('status', '=', '5')
		->get();
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		return view('content.projects', ['projects' => $projects, 'user' => $userdata, 'edit_projects' => $edit_projects, 'notifications' => $notifications], compact('my_projects'));
	}

	public function logout() {
		Cas::logout();
	}

	public function reorder($id) {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$project = Projects::where('id', '=', $id)->first();
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->where('project_owner', '=', $project->project_owner)
		->where('priority', '=', $project->priority)
		->whereNotIn('status', [5,6])
		->orderBy('priority')
		->orderBy('order')
		->get();
		return view('content.reorder', ['movingProject' => $project, 'relatedProjects' => $projects]);
	}

	public function reorder_process(ReorderRequest $request) {
		$project_id = $request['movingProjectId'];
		$values = $request["order"];
		foreach ($values as $id => $val) {
			$project = Projects::where('id', '=', $id)->first();
			$project['order'] = $val;
			$project->save();
		}
		return redirect("request/" . $project_id)->withSuccess("Successfully reordered projects.");
	}


	public function view_notifications() {
		$user_id = Helpers::full_authenticate()->id;
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
		$get_notifications = $get_projects->union($comments)->orderBy('updated_at', 'desc')->get();
		$in_notification_table = CheckNotifications::where('notif_check_user_id', '=', $user_id)->first();
		$lastcheck = "";
		if ($in_notification_table == NULL) {
			$request['notif_check_user_id'] = $user_id;
			CheckNotifications::create($request);
			$lastcheck = CheckNotifications::where('notif_check_user_id', '=', $user_id)->first();
		} else {
			$lastcheck = CheckNotifications::where('notif_check_user_id', '=', $user_id)->first();
		}
		return view('notifications.view', ['notifications' => $get_notifications, 'last_check' => $lastcheck]);
	}

	public function create() {
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		if ($user_details->admin == '1') {
			//get owners to populate project owners
			$owners = Owners::where('active', '=', 'Active')->lists('name', 'id');
			return view('content.create', ['owners' => $owners, 'users' => $user_details]);
		} else {
			return redirect('requests')->withErrors(['no' => 'You are not authorized for this function.']);
		}
	}

	public function delete($id) {
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		$project = Projects::where('id', '=', $id)->first();
		if ($user_details->admin == '1') {
			//return with message
			//reorder
			$reorder_projects = Projects::where('project_owner', '=', $project->project_owner)
			->where('priority', '=', $project->priority)
			->whereNotIn('status', [5,6])
			->where('order', '>', $project->order)
			->get();
			//move everything else up if there's something to move up
			if (count($reorder_projects) > 0) {
				foreach ($reorder_projects as $reorder_project) {
					$anchor_order = $reorder_project['order'];
					$reorder_project['order'] = $anchor_order - 1;
					$reorder_project->save();
				}
			}
			//if admin, then delete project
			Projects::where('id', '=', $id)->delete();
			//delete comments
			Comments::where('comment_project_id', '=', $id)->delete();
			//delete notification assignments
			Notifications::where('notif_project_id', '=', $id)->delete();
			return redirect('requests')->withSuccess("Successfully deleted project.");
		} else {
			return redirect('requests')->withErrors(['no' => 'You are not authorized for this function.']);
		}
	}

	public function store(ProjectsRequest $request) {
		//Check to See if duplicate order and priority already exists.
		$duplicateOrder = Projects::where('priority', '=', $request->priority)
		->where('order', '=', $request->order)
		->where('project_owner', '=', $request->project_owner)
		->whereNotIn('status', [5,6])
		->first();
		if ($duplicateOrder == NULL) {
			Projects::create($request->all());
			return redirect('requests')->withSuccess("Successfully created project.");
		} else {
			//return redirect()->back()->withErrors(['order' => 'The combination of Priority and Order you are using already exists. Please try a different order or changing the priority.'])->withInput($request->except('order'));
			return redirect()->back()->withErrors(['order' => 'The combination of Priority and Order you are using already exists. Please try a different order or changing the priority.'])->withInput();
		}
	}

	public function get_project($id) {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')->select('requests.*', 'project_owners.name')->where('requests.id', '=', $id)->first();
		$comments = Comments::leftJoin('users', 'comment_user_id', '=', 'users.id')
		->select('project_comments.*', 'users.fullname')
		->where('comment_project_id', '=', $id)
		->orderBy('created_at', 'asc')
		->get();
		$sprints = Sprints::orderBy('sprintNumber', 'desc')->lists('sprintNumber', 'id');
		if ($projects != NULL) {
			Session::flash('url', Request::server('HTTP_REFERER'));
			return view('content.view', ['projects' => $projects, 'user' => $userdata, 'my_projects' => $my_projects, 'comments' => $comments, 'sprints' => $sprints]);
		} else {
			return redirect()->back();
		}
	}

	public function add_comment(CommentsRequest $request) {
		$user_id = Helpers::full_authenticate()->id;
		$request['comment_user_id'] = $user_id;
		Comments::create($request->all());
		return redirect()->back()->withSuccess("Successfully added comment.");
	}

	public function remove_comment($id) {
		Comments::where('id', '=', $id)->delete();
		return redirect()->back()->withSuccess("Successfully removed comment.");
	}

	public function edit($id) {
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		$my_projects = Projects::leftJoin('user_mappings', 'requests.project_owner', '=', 'user_mappings.owner_id')
		->where('user_mappings.user_id', '=', $user_id)
		->where('user_mappings.edit', '=', 1)
		->select('requests.*', 'user_mappings.user_id', 'user_mappings.owner_id')
		->lists('requests.id');
		if ($user_details->admin == '1' || in_array($id, $my_projects)) {
			$project = Projects::findOrFail($id);
			$owners = Owners::where('active', '=', 'Active')->lists('name', 'id');
			return view('content.edit', ['project' => $project, 'user_details' => $user_details], compact('owners'));
		} else {
			return redirect()->back()->withErrors(['noauth' => 'You are not authorized for this function.']);
		}
	}

	public function translate_status($code) {
		if ($code == "") {
			return "Unknown";
		} else if ($code == "0") {
			return "Needs Review";
		} else if ($code == "1") {
			return "Pending";
		} else if ($code == "2") {
			return "Ready to Schedule";
		} else if ($code == "3") {
			return "Scheduled / In Progress";
		} else if ($code == "4") {
			return "Refer to Oracle";
		} else if ($code == "5") {
			return "Deferred";
		} else if ($code == "6") {
			return "Completed";
		} else if ($code == "7") {
			return "New";
		}
	}

	public function translate_category($code) {
		if ($code == "0") {
			return "Undetermined";
		} else if ($code == "1") {
			return "Category 1";
		} else if ($code == "2") {
			return "Category 2";
		} else if ($code == "3") {
			return "Category 3";
		} else if ($code == "4") {
			return "Category 4";
		} else {
			return "Any";
		}
	}

	public function translate_priority($code) {
		if ($code == "0") {
			return "High";
		} else if ($code == "1") {
			return "Medium";
		} else if ($code == "2") {
			return "Low";
		} else {
			return "Any";
		}
	}

	public function update_status(ReorderRequest $request) {
		//get user details
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		//update project
		$id = $request['project_id'];
		$project = Projects::where('id', '=', $id)->first();
		$name = $project->request_name;
		$oldstat = $project->status;
		$project['status'] = $request['status'];
		$project->save();
		//if there's a comment, let's enter it into the comments table

		$commentRequest['comment_user_id'] = $user_id;
		$commentRequest['comment_project_id'] = $id;
		$comment = "Changed project status from <strong>" . ProjectsController::translate_status($oldstat) . "</strong> to <strong>" . ProjectsController::translate_status($request['status']) . "</strong>";
		if ($request['comment_text'] != "") {
			$comment .= "<br><p class='text-muted'>Note: " . $request['comment_text'] . "</p>";
		}
		$commentRequest['comment'] = $comment;
		Comments::create($commentRequest);
		return redirect()->back()->withSuccess("Succesfully updated the status of $name.");
	}

	public function mark_complete(ReorderRequest $request) {
		$user_id = Helpers::full_authenticate()->id;
		$id = $request['project_id'];
		$project = Projects::where('id', '=', $id)->first();
		$name = $project->request_name;
		$priority = $project->priority;
		$reorder_projects = Projects::where('project_owner', '=', $project->project_owner)
		->where('priority', '=', $priority)
		->whereNotIn('status', [5,6])
		->where('order', '>', $project->order)
		->get();
		//mark complete
		$project['status'] = 6;
		$project->save();
		//move everything else up if there's something to move up
		if (count($reorder_projects) > 0) {
			foreach ($reorder_projects as $reorder_project) {
				$anchor_order = $reorder_project['order'];
				$reorder_project['order'] = $anchor_order - 1;
				$reorder_project->save();
			}
		}
		$commentRequest['comment_user_id'] = $user_id;
		$commentRequest['comment_project_id'] = $id;
		$comment = "Changed project status to <strong>Complete.</strong>";
		if ($request['comment_text'] != "") {
			$comment .= "<br><p class='text-muted'>Note: " . $request['comment_text'] . "</p>";
		}
		$commentRequest['comment'] = $comment;
		Comments::create($commentRequest);
		return redirect()->back()->withSuccess("Marked Project as Complete.");
	}

	public function update($id, ProjectsRequest $request) {
		$project = Projects::findOrFail($id);
		$project->update($request->all());
		return redirect('request/' . $id)->withSuccess("Successfully updated Project.");
	}

	public function project_search() {
		$owners = Owners::where('active', '=', 'Active')->orderby('name', 'asc')->get();
		return view('content.search', ['owners' => $owners]);
	}

	public function process_search() {
		$input = Request::all();
		$query['sterm'] = $input['sq_n'];
		if (!isset($input['sq_n']) || $input['sq_n'] == "") {
			$input['sq_n'] = "%";
			$query['sterm'] = "";
		}
		if (!isset($input['sq_s']) || $input['sq_s'] == "" || $input['sq_s'][0] == "") {
			$input['sq_s'] = "";
			$query['status'] = "Any";
		}
		else {
			if(is_array($input['sq_s'])) {
				$status_list = [];
				foreach ($input['sq_s'] as $key => $value) {
    				array_push($status_list, ProjectsController::translate_status($value));
				}
				$query['status'] = implode($status_list, ', ');
			}
			else {
				$query['status'] = ProjectsController::translate_status($input['sq_s']);
			}
		}
		if (!isset($input['sq_p']) || $input['sq_p'] == "" || $input['sq_p'][0] == "") {
			$input['sq_p'] = "";
			$query['priority'] = "Any";
		}
		else {
			if(is_array($input['sq_p'])) {
				$priorities = [];
				foreach ($input['sq_p'] as $key => $value) {
    				array_push($priorities, ProjectsController::translate_priority($value));
				}
				$query['priority'] = implode($priorities, ', ');
			}
			else {
				$query['priority'] = ProjectsController::translate_priority($input['sq_p']);
			}
		}
		if (!isset($input['sq_o']) || $input['sq_o'] == "" || $input['sq_o'][0] == "" ) {
			$input['sq_o'] = "";
			$query['owner'] = "Any";
		}
		else {
			if(is_array($input['sq_o'])) {
				$owners = [];
				foreach ($input['sq_o'] as $key => $value) {
    				array_push($owners, Owners::where('id', '=', $value)->pluck('name'));
				}
				$query['owner'] = implode($owners, ', ');
			}
			else {
				$query['owner'] = Owners::where('id', '=', $input['sq_o'])->pluck('name');
			}
		}
		if (!isset($input['sq_c'])) {
			$input['sq_c'] = "%";
			$query['cascade'] = "Any";
		} else {
			$query['cascade'] = $input['sq_c'];
			if ($query['cascade'] == "C") {
				$query['cascade'] = "Yes";
			} else {
				$query['cascade'] = "Any";
			}
		}
		if (!isset($input['sq_co'])) {
			$input['sq_co'] = "%";
			$query['completed'] = "No";
		} else {
			$query['completed'] = $input['sq_co'];
			if ($query['completed'] == "Y") {
				$query['completed'] = "Yes";
			} else {
				$query['completed'] = "No";
			}
		}
		if (!isset($input['sq_ip']) || $input['sq_ip'] == "" || $input['sq_ip'][0] == "") {
			$input['sq_ip'] = "";
			$query['ip'] = "Any";
		}
		else {
			if(is_array($input['sq_ip'])) {
				$categories = [];
				foreach ($input['sq_ip'] as $key => $value) {
    				array_push($categories, ProjectsController::translate_category($value));
				}
				$query['ip'] = implode($categories, ', ');
			}
			else {
				$query['ip'] = ProjectsController::translate_category($input['sq_ip']);
			}
		}
		$statement = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')->select('requests.*', 'project_owners.name')
		->where('request_name', 'LIKE', '%' . $input['sq_n'] . '%')
		//->where('priority', 'LIKE', $input['sq_p'])
		->where('cascade_flag', 'LIKE', '%' . $input['sq_c'] . '%')
		->orderBy('priority', 'asc')
		->orderBy('order');
		if(is_array($input['sq_ip'])) {
			$statement->whereIn('inst_priority', $input['sq_ip']);
		}
		else {
			$statement->where('inst_priority', 'LIKE', '%' . $input['sq_ip'] . '%');
		}
		if(is_array($input['sq_p'])) {
			$statement->whereIn('priority', $input['sq_p']);
		}
		else {
			$statement->where('priority', 'LIKE', '%' . $input['sq_p'] . '%');
		}
		if(is_array($input['sq_s'])) {
			$statement->whereIn('status', $input['sq_s']);
		}
		else {
			$statement->where('status', 'LIKE', '%' . $input['sq_s'] . '%');
		}
		if(is_array($input['sq_o'])) {
			$statement->whereIn('project_owner', $input['sq_o']);
		}
		else {
			$statement->where('project_owner', 'LIKE', '%' . $input['sq_o'] . '%');
		}
		if($query['completed'] != "Yes") {
			$statement->where('status', 'NOT LIKE', '6');
		}
		$results = $statement->get();
		return view('content.results', ['projects' => $results, 'query' => $query]);
	}


}
