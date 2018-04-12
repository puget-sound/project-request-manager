<?php

namespace App\Http\Controllers;
use App\Owners;
use App\Projects;
use App\Sprints;
use App\ProjectSprintPhase;
use App\ProjectSprintStatus;
use App\ERPReportCategory;
use Helpers;
use App\Http\Requests\SprintsRequest;
use App\Http\Requests\EmptyRequest;
use Carbon\Carbon;

class SprintsController extends Controller {
	public function show() {
		$details_sprints = array();
		$details_sprints = SprintsController::fetch_display_sprints();
		return view('sprints.show', ['sprints' => $details_sprints]);
	}

	public function show_to_all() {
		$details_sprints = array();
		$details_sprints = SprintsController::fetch_display_sprints();
		$all_sprints = \App\Sprints::get();
		$current_sprint = '';
		$today = Carbon::today();

		for ($i = 0; $i < count($all_sprints); $i++) {
			if ($today >= $all_sprints[$i]->sprintStart && ($today <= $all_sprints[$i]->sprintEnd || $today < $all_sprints[$i+1]->sprintStart)){
				$current_sprint = $all_sprints[$i];
			}
		}
		$current_sprint_end = new Carbon($current_sprint->sprintEnd);

    $days_to_sprint_end = $current_sprint_end->diffInDays($today);
		return view('sprints.show-to-all', ['sprints' => $details_sprints, 'days_to_sprint_end' => $days_to_sprint_end]);
	}

	public function create() {
		return view('sprints.create');
	}

	public function store(SprintsRequest $request) {
		Sprints::create($request->all());
		return redirect('sprints')->withSuccess("Successfully created sprint.");
	}

	public function view($sprintNumber) {
		$sprint = Sprints::where('sprintNumber', '=', $sprintNumber)->first();
		$sprintNumber = $sprint['sprintNumber'];
		$sprint_phases = ProjectSprintPhase::orderBy('id', 'asc')->get()->lists('name', 'id');
		$sprint_statuses = ProjectSprintStatus::orderBy('id', 'asc')->get()->lists('name', 'id');
		$projects = $sprint->projects()->leftJoin('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name as project_owner_name')
		->orderBy('request_name')
		->get();
		foreach ($projects as $this_project) {
		  $these_sprints_display = [];
		foreach ($this_project->sprints()->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
		  array_push($these_sprints_display, $this_sprint->sprintNumber);
		}
		$this_project->sprints_display = implode($these_sprints_display, ', ');
		}
		$categories = ERPReportCategory::orderBy('name', 'ASC')->get();
		return view('sprints.view', ['projects' => $projects, 'sprint' => $sprint, 'sprint_phases' => $sprint_phases, 'sprint_statuses' => $sprint_statuses, 'categories' => $categories]);
	}

	public function project_schedule($sprintNumber) {
		$sprint = Sprints::where('sprintNumber', '=', $sprintNumber)->first();
		$sprintNumber = $sprint['sprintNumber'];
		/*$projects = $sprint->projects()->leftJoin('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*','project_owners.name as project_owner_name')
		->orderBy('erp_report_category_id')
		->get();*/
		$sprintProjects = $sprint->projects()->where('hide_from_reports', '=', '0')->get();
		foreach($sprintProjects as $sprintProject) {
			$phase = ProjectSprintPhase::where('id', '=', $sprintProject->pivot->project_sprint_phase_id)->first();
			$owner = Owners::where('id', '=', $sprintProject->project_owner)->first();
			$sprintProject->project_owner_name = $owner->name;
			if($phase)
				$sprintProject->phaseName = $phase->name;
			else {
				$sprintProject->phaseName = '';
			}
			// assign the 'General' category if none is assigned
			if(!$sprintProject->erp_report_category_id) {
				$sprintProject->erp_report_category_id = 1;
			}
		}
		$categories = ERPReportCategory::orderBy('name', 'ASC')->get();
		return view('sprints.schedule', ['sprint' => $sprint, 'sprintProjects' => $sprintProjects, 'categories' => $categories]);
	}

	public function accomplishments($sprintNumber) {
		$sprint = Sprints::where('sprintNumber', '=', $sprintNumber)->first();
		$sprintNumber = $sprint['sprintNumber'];
		$sprintProjects = $sprint->projects()->where('hide_from_reports', '=', '0')->wherePivot('project_sprint_status_id','!=','null')->get();
		foreach($sprintProjects as $sprintProject) {
			$sprintStatus = ProjectSprintStatus::where('id', '=', $sprintProject->pivot->project_sprint_status_id)->first();
			$owner = Owners::where('id', '=', $sprintProject->project_owner)->first();
			$sprintProject->project_owner_name = $owner->name;
			if($sprintStatus)
				$sprintProject->sprintStatus = $sprintStatus->name;
			else {
				$sprintProject->sprintStatus = '';
			}
			// assign the 'General' category if none is assigned
			if(!$sprintProject->erp_report_category_id) {
				$sprintProject->erp_report_category_id = 1;
			}
		}
		$categories = ERPReportCategory::orderBy('name', 'ASC')->get();
		return view('sprints.accomplishments', ['sprint' => $sprint, 'sprintProjects' => $sprintProjects, 'categories' => $categories]);
	}

	public function edit($sprintNumber) {
		$sprint = Sprints::where('sprintNumber', '=', $sprintNumber)->first();
		return view('sprints.edit', ['sprint' => $sprint]);
	}

	public function update($sprintNumber, SprintsRequest $request) {
		$sprint = Sprints::where('sprintNumber', '=', $sprintNumber)->first();
		$sprint->update($request->all());
		return redirect('sprints/')->withSuccess("Successfully updated Sprint.");
	}

	public function assign_project(EmptyRequest $request) {
		$id = $request['project_id'];
		$project = Projects::where('id', '=', $id)->first();
		$sprint = $request['sprint'];
		$assign_type = $request['sprint_assign_type'];
		$from_sprints_ids = explode(",", $request['this_sprint_ids']);
		// remove all sprint assignments
		$project->sprints()->detach();
		$project->sprints()->attach($sprint);
		$assigned_sprints = $project->sprints()->orderBy('sprints_id', 'ASC')->get();
		$to_sprints_message = "";
		foreach ($assigned_sprints as $this_sprint) {
			$to_sprints_message .= "<a href='/sprint/" . $this_sprint->sprintNumber . "'>Sprint " . $this_sprint->sprintNumber . "</a>";
			if($this_sprint != $assigned_sprints->last()) {
					$to_sprints_message .= ", ";
			}
		}
		if(count($sprint) < 1) {
			$project['status'] = 2;
			$to_sprints_message = "<em>No Sprint</em>";
		}
		else {
			$project['status'] = 3;
		}
		$project->save();
		$from_sprints_message = "";
		$from_sprints = Sprints::findMany($from_sprints_ids);
		foreach ($from_sprints as $this_from_sprint) {
			$from_sprints_message .= "<a href='/sprint/" . $this_from_sprint->sprintNumber . "'>Sprint " . $this_from_sprint->sprintNumber . "</a>";
			if($this_from_sprint != $from_sprints->last()) {
					$from_sprints_message .= ", ";
			}
		}
		if($assign_type == 'Addto') {
			return redirect()->back()->withSuccess("Successfully added to $to_sprints_message");
		}
		else {
			return redirect()->back()->withSuccess("Successfully changed from $from_sprints_message to $to_sprints_message");
		}
	}

	public function extend_project(EmptyRequest $request) {
		$id = $request['project_id'];
		$sprint_id = $request['sprint_id'];
		$sprint = Sprints::where('id', '=', $sprint_id)->first();
		$project = Projects::where('id', '=', $id)->first();
		$next_sprint_id = (int)$sprint_id + 1;
		$next_sprint = Sprints::where('id', '=', $next_sprint_id)->first();
		if (! $project->sprints->contains($next_sprint->id)) {
			$project->sprints()->attach($next_sprint_id);
			$project->save();
		}

		return redirect()->back()->withSuccess("Successfully extended '" . $project->request_name . "' into Sprint " . $next_sprint->sprintNumber);
	}

	public function move_project(EmptyRequest $request) {
		$id = $request['project_id'];
		$sprint_id = $request['sprint_id'];
		$sprint = Sprints::where('id', '=', $sprint_id)->first();
		$project = Projects::where('id', '=', $id)->first();
		$next_sprint_id = (int)$sprint_id + 1;
		$next_sprint = Sprints::where('id', '=', $next_sprint_id)->first();
		$project->sprints()->detach();
		$project->sprints()->attach($next_sprint_id);
		$project->save();

		return redirect()->back()->withSuccess("Successfully moved '" . $project->request_name . "' to Sprint " . $next_sprint->sprintNumber);
	}

	public function deassign_project(EmptyRequest $request) {
		$id = $request['project_id'];
		$project = Projects::where('id', '=', $id)->first();
		$these_sprints_display = [];
		foreach ($project->sprints()->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
			array_push($these_sprints_display, $this_sprint->sprintNumber);
		}
		$this_sprint_numbers = implode($these_sprints_display, ', ');
		$project['status'] = 2;
		$project->sprints()->detach();
		$project->save();
		return redirect()->back()->withSuccess("Successfully removed from Sprint(s) " . $this_sprint_numbers);
	}
	public function set_project_phase_status(EmptyRequest $request) {
		$project_id = $request['project_id'];
		$sprint_id = $request['sprint_id'];
		$phase_id = $request['phase_id'];
		$this_phase = ProjectSprintPhase::where('id', '=', $phase_id)->first();
		$status_id = $request['status_id'];
		$sprint = Sprints::where('id', '=', $sprint_id)->first();
		$sprint->projects()->detach($project_id);
		$sprint->projects()->attach($project_id, ['project_sprint_phase_id' => $phase_id, 'project_sprint_status_id' => $status_id]);
		$project = Projects::where('id', '=', $project_id)->first();
		return redirect()->back()->withSuccess("Successfully set phase and status for <strong>" . $project->request_name . "</strong>");
	}

	public function fetch_display_sprints() {
		$sprints = Sprints::orderBy('sprintNumber', 'ASC')->get();
		$details_sprints = array();
		foreach ($sprints as $sprint) {
			$sprintTotal = 0;
			$sprintComplete = 0;
			foreach($sprint->projects()->get() as $sprintProject) {
				$hasFutureSprint = false;
				foreach($sprintProject->sprints()->get() as $projectSprint){
					if($projectSprint->sprintNumber > $sprint->sprintNumber) {
						$hasFutureSprint = true;
					}
				}
				if(!$hasFutureSprint) {
					$sprintTotal++;
					if($sprintProject->status == "6") {
						$sprintComplete++;
					}
				}
			}
			$sprint->sprintTotal = $sprintTotal;
			$sprint->sprintComplete = $sprintComplete;
			if ($sprintTotal > 0) {
			$sprint['completed'] = round(($sprintComplete / $sprintTotal) * 100);
			}
			else {
				$sprint['completed'] = 0;
			}
			$details_sprints[] = $sprint;
		}
		return $details_sprints;
	}
}
