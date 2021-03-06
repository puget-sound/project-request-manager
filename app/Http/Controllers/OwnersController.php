<?php

namespace App\Http\Controllers;
use App\Owners;
use App\Users;
use App\UserMappings;
use App\Http\Requests\OwnersRequest;
use App\Http\Requests\UserMappingsRequest;
use App\Classes\LiquidPlannerClass;
use Request;

class OwnersController extends Controller {
	public function show() {
		$owners = Owners::where('active', '=', 'Active')->orderby('name', 'asc')->get();
		return view('owners.show', ['owners' => $owners]);
	}

	public function delete($id) {
		$select = Owners::where('id', '=', $id)->first();
		$select->active = 'Inactive';
		$select->save();
		return redirect()->back();
	}

	public function view_details($id) {
		$select = Owners::where('id', '=', $id)->first();
		$users = Users::where('active', '=', 'Active')
		->whereNotIn('id', UserMappings::where('owner_id', '=', $id)->select('user_id')->lists('user_id'))
		->orderBy('fullname', 'asc')
		->lists('fullname', 'id');
		$group_users = Users::join('user_mappings', 'user_mappings.user_id', '=', 'users.id')->where('owner_id', '=', $id)->get();
		$email = env('LP_EMAIL');
		$password = env('LP_PASSWORD');

		$lp = new LiquidPlannerClass($email, $password);
		$lp->workspace_id = env('LP_WORKSPACE');
		$clients = $lp->clients();
		foreach($clients as $client) {
			if($client->id != '-1' && $client->id != '0')
    	$lp_clients[$client->id] = $client->name;
		}
		asort($lp_clients);
		$lp_clients = array('' => 'None') + $lp_clients;
		return view('owners.manage', ['owner' => $select, 'group_users' => $group_users, 'lp_clients' => $lp_clients], compact('users'));
	}

	public function map_user(UserMappingsRequest $request) {
		UserMappings::create($request->all());
		return redirect()->back();
	}

	public function unmap_user($owner_id, $user_id) {
		UserMappings::where('user_id', '=', $user_id)->where('owner_id', '=', $owner_id)->delete();
		$select_owner = Owners::where('id', '=', $owner_id)->pluck('name');
		$select = Users::where('id', '=', $user_id)->pluck('fullname');
		return redirect()->back()->withSuccess("Removed $select from $select_owner.");
	}

	public function edit_lp_id() {
		$input = Request::all();
		$owner = Owners::where('id', '=', $input['owner_id'])->first();
		$owner->lp_id = $input['lp_id'];
		$owner->save();
		return redirect()->back()->withSuccess("LiquidPlanner Client saved.");
	}

	public function edit_google_id() {
		$input = Request::all();
		$owner = Owners::where('id', '=', $input['owner_id'])->first();
		$owner->google_id = $input['google_id'];
		$owner->save();
		return redirect()->back()->withSuccess("Google Drive saved.");
	}

	public function store(OwnersRequest $request) {
		//Check to See if duplicate order and priority already exists.
		$duplicateOwner = Owners::where('name', '=', $request->name)->first();
		if ($duplicateOwner == NULL) {
			$owner = Owners::create($request->all());
			//connect to LiquidPlanner and create new client
			$email = env('LP_EMAIL');
			$password = env('LP_PASSWORD');

			$lp = new LiquidPlannerClass($email, $password);
			$lp->workspace_id = env('LP_WORKSPACE');
			$client = array('name'=> $owner->name);
			$result = $lp->create_client($client);
			$owner['lp_id'] = "$result->id";
			$owner->save();
			return redirect('owners')->withSuccess("Successfully created ".$owner->name);
		} else {
			//return redirect()->back()->withErrors(['order' => 'The combination of Priority and Order you are using already exists. Please try a different order or changing the priority.'])->withInput($request->except('order'));
			return redirect()->back()->withErrors(['name' => 'This project owner already exists. Please try again.'])->withInput();
		}
	}
}
