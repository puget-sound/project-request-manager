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
		$sprints = Sprints::get();
		$details_sprints = array();
		foreach ($sprints as $sprint) {
			$sprintTotal = count(Projects::where('sprint', '=', $sprint->sprintNumber)->get());
			$sprintComplete = count(Projects::where('sprint', '=', $sprint->sprintNumber)->where('status', '=', '6')->get());
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
		$sprints = Sprints::get();
		$sprintNumber = $sprint['sprintNumber'];
		$projects = Projects::join('project_owners', 'requests.project_owner', '=', 'project_owners.id')
		->select('requests.*', 'project_owners.name')
		->where('sprint', '=', $sprintNumber)
		->orderBy('priority')
		->orderBy('order')
		->get();
		return view('sprints.view', ['projects' => $projects, 'sprint' => $sprint, 'sprints' => $sprints]);
	}

	public function assign_project(EmptyRequest $request) {
		$id = $request['project_id'];
		$sprint = $request['sprint'];
		$sprintNumber = Sprints::where('id', '=', $sprint)->pluck('sprintNumber');
		$project = Projects::where('id', '=', $id)->first();
		$project['sprint'] = $sprintNumber;
		$project['status'] = 3;
		$project->save();
		return redirect()->back()->withSuccess("Successfully Assigned " . $project->request_name . " to Sprint " . $sprintNumber);
	}

	public function deassign_project(EmptyRequest $request) {
		$id = $request['project_id'];
		$project = Projects::where('id', '=', $id)->first();
		$sprint = $project->sprint;
		$project['status'] = 2;
		$project['sprint'] = "";
		$project->save();
		return redirect()->back()->withSuccess("Successfully Deassigned " . $project->request_name . " from Sprint " . $sprint);
	}
}