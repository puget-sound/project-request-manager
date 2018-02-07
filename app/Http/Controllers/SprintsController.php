<?php

namespace App\Http\Controllers;
use App\Owners;
use App\Projects;
use App\Sprints;
use Helpers;
use App\Http\Requests\SprintsRequest;
use App\Http\Requests\EmptyRequest;
use Carbon\Carbon;

class SprintsController extends Controller {
	public function show() {
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
		return view('sprints.show', ['sprints' => $details_sprints]);
		//return view('users.show', ['users' => $users]);
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
		$projects = $sprint->projects()->join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->orderBy('priority')
		->orderBy('order')
		->get();
		foreach ($projects as $this_project) {
		  $these_sprints_display = [];
		foreach ($this_project->sprints()->orderBy('sprints_id', 'ASC')->get() as $this_sprint) {
		  array_push($these_sprints_display, $this_sprint->sprintNumber);
		}
		$this_project->sprints_display = implode($these_sprints_display, ', ');
		}
		return view('sprints.view', ['projects' => $projects, 'sprint' => $sprint]);
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
		$project['status'] = 3;
		$project->save();
		$assigned_sprints = $project->sprints()->orderBy('sprints_id', 'ASC')->get();
		$to_sprints_message = "";
		foreach ($assigned_sprints as $this_sprint) {
			$to_sprints_message .= "<a href='/sprint/" . $this_sprint->sprintNumber . "'>Sprint " . $this_sprint->sprintNumber . "</a>";
			if($this_sprint != $assigned_sprints->last()) {
					$to_sprints_message .= ", ";
			}
		}
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
}
