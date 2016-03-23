<?php

namespace App\Http\Controllers;
use App\Users;
use App\UserMappings;
use App\Http\Requests\UsersRequest;
use Helpers;

class UsersController extends Controller {
	public function show() {
		$users = Users::where('active', '!=', 'Inactive')->orderBy('admin', 'desc')->orderBy('fullname', 'asc')->get();
		return view('users.show', ['users' => $users]);
	}

	public function add() {
		return view('users.create');
	}

	public function remove($id) {
		// mark user as 'Inactive' in Users table
		$select = Users::where('id', '=', $id)->first();
		$select->active = 'Inactive';
		$select->save();
		// delete any mappings
		$mappings = UserMappings::where('user_id', '=', $id)->delete();
		return redirect()->back()->withSuccess('Removed user and updated appropriate associations.');
	}

	public function store(UsersRequest $request) {
		//Check to See if duplicate order and priority already exists.
		$duplicateUser = Users::where('username', '=', $request->username)->where('active', '=', 'Active')->first();
		if ($duplicateUser == NULL) {
			$fullname = Helpers::getFullName($request->username);
			if ($fullname == "err") {
				return redirect()->back()->withErrors(['name' => 'This username did not match Active Directory records. Please Try Again.'])->withInput();
			} else {
				$request['fullname'] = $fullname; 
				Users::create($request->all());
				return redirect('users');
			}
		} else {
			//return redirect()->back()->withErrors(['order' => 'The combination of Priority and Order you are using already exists. Please try a different order or changing the priority.'])->withInput($request->except('order'));
			return redirect()->back()->withErrors(['name' => 'This user already exists. Please try again.'])->withInput();
		}
	}
}