<?php

namespace App\Http\Controllers;
use App\Owners;
use App\Projects;
use App\Sprints;
use Helpers;
use App\Http\Requests\SprintsRequest;
use App\Http\Requests\EmptyRequest;
use Carbon\Carbon;

class AnalyticsController extends Controller {
	public function analytics() {
		$all_sprints = \App\Sprints::get();
		$current_sprint = '';
		$today = Carbon::today();

		for ($i = 0; $i < count($all_sprints); $i++) {
			if ($today >= $all_sprints[$i]->sprintStart && ($today <= $all_sprints[$i]->sprintEnd || $today < $all_sprints[$i+1]->sprintStart)){
				$current_sprint = $all_sprints[$i]->sprintNumber;
			}
		}
		return view('analytics.analytics', ['current_sprint' => $current_sprint]);
	}
}
