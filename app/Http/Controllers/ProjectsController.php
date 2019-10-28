<?php

namespace App\Http\Controllers;
use App;
use App\Projects;
use App\Owners;
use App\CheckNotifications;
use App\Notifications;
use App\UserMappings;
use App\Users;
use App\ProjectNumber;
use App\ERPReportCategory;
use Session;
use Request;
use App\Sprints;
use DB;
use Helpers;
use App\Http\Requests\ProjectsRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Requests\CommentsRequest;
use App\Http\Requests\NotificationsRequest;
use App\Http\Requests\UnlockProjectRequest;
use App\Comments;
use App\Classes\LiquidPlannerClass;
use Cas;
use Carbon\Carbon;



class ProjectsController extends Controller {
	//Loads Open Projects into projects view throught projects.blade.php

	public function projects_by_owner($owner_id) {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$owner = Owners::where('id', '=', $owner_id)->first();
		$projects = Projects::where('project_owner', '=', $owner_id)
		->whereNotIn('status', [6, 5])
		->orderBy('priority')
		->orderBy('order')
		->get();
		$all_sprints = \App\Sprints::get();
		$current_sprint = '';
		$today = Carbon::today();

		for ($i = 0; $i < count($all_sprints); $i++) {
			if ($today >= $all_sprints[$i]->sprintStart && ($today <= $all_sprints[$i]->sprintEnd || $today < $all_sprints[$i+1]->sprintStart)){
				$current_sprint = $all_sprints[$i]->sprintNumber;
			}
		}
		foreach ($projects as $this_project) {
		  $these_sprints_display = [];
			$this_project->is_future_sprint = false;
			foreach ($this_project->sprints()->where('sprintNumber', '>=', $current_sprint)->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
		  	array_push($these_sprints_display, $this_sprint->sprintNumber);
			}
			$this_project->sprints_display = implode($these_sprints_display, ', ');
			if($this_project->status == "3" && $this_project->sprints()->count() > 0) {
				if($this_project->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber > $current_sprint) {
					$this_project->is_future_sprint = true;
				}
			}
		}
		$notifications = Notifications::where('notif_user_id', '=', $user_id)->select('id as notif_id', 'notif_user_id', 'notif_project_id')->lists('notif_project_id');
		//$projects = Projects::where('id', '=', 1)->orderBy('priority')->orderBy('order')->get();
		return view('content.projects', ['projects' => $projects, 'owner' => $owner, 'user' => $userdata, 'notifications' => $notifications]);
	}

	public function my_open_projects() {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$is_owner = UserMappings::where('user_id', '=', $user_id)->first();
		if($is_owner == '') {
			return redirect('requests/all');
		}
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
		$all_sprints = \App\Sprints::get();
		$current_sprint = '';
		$today = Carbon::today();

		for ($i = 0; $i < count($all_sprints); $i++) {
			if ($today >= $all_sprints[$i]->sprintStart && ($today <= $all_sprints[$i]->sprintEnd || $today < $all_sprints[$i+1]->sprintStart)){
				$current_sprint = $all_sprints[$i]->sprintNumber;
			}
		}
		foreach ($projects as $this_project) {
		  $these_sprints_display = [];
			$this_project->is_future_sprint = false;
			foreach ($this_project->sprints()->where('sprintNumber', '>=', $current_sprint)->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
		  	array_push($these_sprints_display, $this_sprint->sprintNumber);
			}
			$this_project->sprints_display = implode($these_sprints_display, ', ');
			if($this_project->status == "3" && $this_project->sprints()->count() > 0) {
				if($this_project->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber > $current_sprint) {
					$this_project->is_future_sprint = true;
				}
			}
		}
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
		$all_sprints = \App\Sprints::get();
		$current_sprint = '';
		$today = Carbon::today();

		for ($i = 0; $i < count($all_sprints); $i++) {
			if ($today >= $all_sprints[$i]->sprintStart && ($today <= $all_sprints[$i]->sprintEnd || $today < $all_sprints[$i+1]->sprintStart)){
				$current_sprint = $all_sprints[$i]->sprintNumber;
			}
		}
		foreach ($projects as $this_project) {
		  $these_sprints_display = [];
			$this_project->is_future_sprint = false;
			foreach ($this_project->sprints()->where('sprintNumber', '>=', $current_sprint)->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
		  	array_push($these_sprints_display, $this_sprint->sprintNumber);
			}
			$this_project->sprints_display = implode($these_sprints_display, ', ');
			if($this_project->status == "3" && $this_project->sprints()->count() > 0) {
				if($this_project->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber > $current_sprint) {
					$this_project->is_future_sprint = true;
				}
			}
		}
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

	public function create() {
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		$first_owner = Owners::orderBy('id', 'ASC')->first()->id;
		if ($user_details->isAdmin()) {
			//get owners to populate project owners
			$owners = Owners::where('active', '=', 'Active')->lists('name', 'id');
			$erp_report_categories = ERPReportCategory::all()->lists('name', 'id');
			return view('content.create', ['owners' => $owners, 'users' => $user_details, 'first_owner' => $first_owner, 'erp_report_categories' => $erp_report_categories]);
		} else {
			return redirect('requests')->withErrors(['no' => 'You are not authorized for this function.']);
		}
	}

	public function delete($id) {
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		$project = Projects::where('id', '=', $id)->first();
		if ($user_details->isAdmin()) {
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
			$project = Projects::create($request->all());
			$project_number_counter = ProjectNumber::all()->last();
			$project_number = $project_number_counter->project_number;
			if(strlen($project_number) < 4) {
				$project_number = '0'.$project_number;
			}
			$project->project_number = 'P'.$project_number;
			// set project status to 'New'
			$project->status = '7';
			$project->save();
			DB::table('project_number')->whereId($project_number_counter->id)->increment('project_number');

			return redirect("projects/" . $project->project_owner)->withSuccess("Successfully created project.");
		} else {
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
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')->select('requests.*', 'project_owners.name', 'project_owners.signoff_owner', 'project_owners.google_id')->where('requests.id', '=', $id)->first();
		$comments = Comments::leftJoin('users', 'comment_user_id', '=', 'users.id')
		->select('project_comments.*', 'users.fullname')
		->where('comment_project_id', '=', $id)
		->orderBy('created_at', 'asc')
		->get();
		$url = 'http://signoff.pugetsound.edu/php/loadProjectOwners.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
		$contents = curl_exec($ch);
		curl_close ($ch);
		$signoffOwners = json_decode($contents);
		/*$this_sprint = Sprints::where('sprintNumber', '=', $projects->sprint)->first();
		$this_sprint_id = NULL;
		if($this_sprint != NULL) {
			$this_sprint_id = $this_sprint->id;
		}*/
		$owners = Owners::where('active', '=', 'Active')->orderby('name', 'asc')->whereNotNull('google_id')->get();
		$these_sprints = [];
		$these_sprints_display = [];
		foreach ($projects->sprints()->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
    	array_push($these_sprints, $this_sprint->id);
			array_push($these_sprints_display, $this_sprint->sprintNumber);
		}
		$this_sprint_id = implode($these_sprints, ',');
		$this_sprint_numbers = implode($these_sprints_display, ', ');
		$all_sprints = \App\Sprints::get();
		$current_sprint = '';
		$today = Carbon::today();

		for ($i = 0; $i < count($all_sprints); $i++) {
			if ($today >= $all_sprints[$i]->sprintStart && ($today <= $all_sprints[$i]->sprintEnd || $today < $all_sprints[$i+1]->sprintStart)){
				$current_sprint = $all_sprints[$i]->sprintNumber;
			}
		}
		$sprints = Sprints::orderBy('sprintNumber', 'asc')->where('sprintNumber', '>=', $current_sprint - 1)->get()->lists('sprint_info', 'id');
		$hours = "";
		if($projects['lp_id'] != "") {
			$email = env('LP_EMAIL');
			$password = env('LP_PASSWORD');

			$lp = new LiquidPlannerClass($email, $password);
			$lp->workspace_id = env('LP_WORKSPACE');
			$lp->project_id = $projects['lp_id'];
			$lp_project = $lp->project();
			//$hours = $lp_project->work;
			//$lp_timesheet_entries = $lp->timesheet_entries();
			if($lp_project->type == "Error") {
				if($lp_project->error == "NotFound") {
					$projects['lp_id'] = null;
					$projects->save();
				}
			}
		}
		if ($projects != NULL) {
			$lp_workspace = env('LP_WORKSPACE');
			$signoff_api_key = env('SIGNOFF_API_KEY');
			$signoff_base_url = env('SIGNOFF_BASE_URL');
			Session::flash('url', Request::server('HTTP_REFERER'));
			return view('content.view', ['projects' => $projects, 'user' => $userdata, 'my_projects' => $my_projects, 'owners' => $owners, 'comments' => $comments, 'sprints' => $sprints, 'this_sprint_id' => $this_sprint_id, 'these_sprints' => $these_sprints, 'this_sprint_numbers' => $this_sprint_numbers, 'lp_workspace'=> $lp_workspace, 'signoff_api_key'=> $signoff_api_key, 'signoff_owners' =>$signoffOwners, 'signoff_base_url' => $signoff_base_url, 'total_hours' => $hours, 'sprint_loop' => $these_sprints_display, 'sprint_display_only' => $this_sprint_numbers]);
		} else {
			return redirect()->back();
		}
	}

	public function send_to_liquidplanner($id) {
		$user_id = Helpers::full_authenticate()->id;
		$userdata = Users::findOrFail($user_id);
		$project = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')->select('requests.*', 'project_owners.name')->where('requests.id', '=', $id)->first();
		$owners = Owners::where('active', '=', 'Active')->where('lp_id', '!=', '')->lists('name', 'id');
		asort($owners);
		$owners = array('' => 'None') + $owners;
		$email = env('LP_EMAIL');
		$password = env('LP_PASSWORD');

		$lp = new LiquidPlannerClass($email, $password);
		$lp->workspace_id = env('LP_WORKSPACE');
		$members = $lp->members();
		foreach($members as $member) {
			if($member->id != '-1' && $member->id != '0')
    	$lp_owners[$member->id] = $member->user_name;
		}
		asort($lp_owners);
		$lp_parent = Array(
    env('LP_PARENT') => "EIS Projects In-Progress",
    env('LP_PS_MNT_PARENT') => "PeopleSoft Maintenance");
		if ($project != NULL) {
			Session::flash('url', Request::server('HTTP_REFERER'));
			return view('content.send', ['project' => $project, 'user' => $userdata, 'owners' => $owners, 'lp_owners' => $lp_owners, 'lp_parent' => $lp_parent]);
		} else {
			return redirect()->back();
		}
	}

	public function process_send() {
		$base_url = App::make('url')->to('/');
		$input = Request::all();
		if($input['project_owner'] != '') {
			$client_id = Owners::findOrFail($input['project_owner'])->lp_id;
		}
		else {
			$client_id = '';
		}
		$project_id = $input['project_id'];
		$lp_owner = $input['lp_owner'];
		$prm_project = Projects::where('id', '=', $project_id)->first();
		// if project has no project number
		if($prm_project->project_number == null){
			// assign new project number
			$project_number_counter = ProjectNumber::all()->last();
			$project_number = $project_number_counter->project_number;
			if(strlen($project_number) < 4) {
				$project_number = '0'.$project_number;
			}
			$prm_project->project_number = 'P'.$project_number;
			$prm_project->save();
			DB::table('project_number')->whereId($project_number_counter->id)->increment('project_number');
		}

		$email = env('LP_EMAIL');
		$password = env('LP_PASSWORD');

		$lp = new LiquidPlannerClass($email, $password);
		$lp->workspace_id = env('LP_WORKSPACE');

		$project = array('parent_id' => $input['lp_parent'], 'name'=> $input['request_name'], 'external_reference' => $prm_project->project_number, 'client_id' => $client_id, 'assignments' => array(array('person_id' => $lp_owner)));
		$result = $lp->create_project($project);
		$prm_project['lp_id'] = "$result->id";
		$prm_project->save();

		$link = array( 'description' => 'PRM project', 'item_id' => $result->id, 'url'=>"$base_url/request/".$input['project_id']);
		$link_result = $lp->create_link($link);
		return redirect("request/" . $input['project_id'])->withSuccess("Successfully sent project to <a href='https://app.liquidplanner.com/space/$lp->workspace_id/projects/show/$result->id' target='_blank'>LiquidPlanner</a>.");
	}

	public function get_project_number() {
		$project = ProjectNumber::all()->last();
		$project_number = $project->project_number;
		DB::table('project_number')->whereId($project->id)->increment('project_number');
		if(strlen($project_number) < 4) {
			$project_number = '0'.$project_number;
		}
		return back()->withSuccess("You just claimed project number <strong>P$project_number</strong>");
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
		if ($user_details->isAdmin() || in_array($id, $my_projects)) {
			$project = Projects::findOrFail($id);
			$owners = Owners::where('active', '=', 'Active')->lists('name', 'id');
			$erp_report_categories = ERPReportCategory::all()->lists('name', 'id');
			return view('content.edit', ['project' => $project, 'user_details' => $user_details, 'erp_report_categories' => $erp_report_categories], compact('owners'));
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
		// set project's last sprint status to 'complete'
		$last_sprint = $project->sprints()->latest()->first();
		$project->sprints()->updateExistingPivot($last_sprint['id'], ['project_sprint_status_id' => 1]);
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
		$hide_from_reports = $request->input('hide_from_reports');
		if ($hide_from_reports === null ) {
    	$project->hide_from_reports = 0;
		} else {
    	$project->hide_from_reports = 1;
		}
		$project->save();
		return redirect('request/' . $id)->withSuccess("Successfully updated Project.");
	}

	public function project_search() {
		$owners = Owners::where('active', '=', 'Active')->orderby('name', 'asc')->get();
		return view('content.search', ['owners' => $owners]);
	}

	public function project_folders() {
		//get user details
		$user_id = Helpers::full_authenticate()->id;
		$user_details = Users::where('id', '=', $user_id)->first();
		$owners = Owners::where('active', '=', 'Active')->orderby('name', 'asc')->whereNotNull('google_id')->get();

		if ($user_details->isLP()) {
			return view('content.folders', ['user_details' => $user_details, 'owners' => $owners]);
		} else {
			return redirect()->back()->withErrors(['noauth' => 'You are not authorized for this function.']);
		}
	}

	public function process_search() {
		$input = Request::all();
		$query['sterm'] = $input['sq_n'];
		if (!isset($input['sq_n']) || $input['sq_n'] == "") {
			$input['sq_n'] = "%";
			$query['sterm'] = "";
		}
		if (!isset($input['sq_nb']) || $input['sq_nb'] == "") {
			$input['sq_nb'] = "%";
			$query['number'] = "";
		}
		else {
				$query['number'] = $input['sq_nb'];
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
		->where('project_number', 'LIKE', '%' . $input['sq_nb'] . '%')
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

	/* Are these functions even used? */

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

	public function unlock(UnlockProjectRequest $request){
		$project = Projects::find($request->request_id);
		$project->status = 1;
		$project->save();
		return redirect()->back()->withSuccess('Unlocked Project');
	}
}
