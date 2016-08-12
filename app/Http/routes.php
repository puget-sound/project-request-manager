<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Project Controller
Route::get('/', function() {return redirect('requests');});
Route::get('projects/search', 'ProjectsController@project_search');
Route::get('projects/search/results', 'ProjectsController@process_search');
Route::get('projects/{owner_id}', 'ProjectsController@projects_by_owner');
Route::get('requests', 'ProjectsController@my_open_projects');
Route::get('requests/completed', 'ProjectsController@my_completed_projects');
Route::get('requests/deferred', 'ProjectsController@my_deferred_projects');
Route::get('requests/all', 'ProjectsController@all_open_projects');
Route::get('requests/all/completed', 'ProjectsController@all_completed_projects');
Route::get('requests/all/deferred', 'ProjectsController@all_deferred_projects');
Route::get('requests/create', 'ProjectsController@create');
Route::get('requests/get-project-number', 'ProjectsController@get_project_number');
Route::post('requests', 'ProjectsController@store');
Route::post('flag/{project_id}', 'AjaxController@flag_project');
Route::get('request', function () {return redirect()->back();});
Route::get('request/{id}/edit', 'ProjectsController@edit');
Route::get('request/{id}/delete', 'ProjectsController@delete');
Route::get('request/{id}', 'ProjectsController@get_project');
Route::get('request/{id}/send-to-liquidplanner', 'ProjectsController@send_to_liquidplanner');
Route::get('projects/{id}/liquidplanner', 'ProjectsController@process_send');
Route::patch('request/{id}', 'ProjectsController@update');
Route::get('request/{id}/reorder', 'ProjectsController@reorder');
Route::patch('request/reorder/process', 'ProjectsController@reorder_process');
Route::post('request/{id}', 'ProjectsController@add_comment');
Route::get('comment/{id}/delete', 'ProjectsController@remove_comment');
Route::get('logout', 'ProjectsController@logout');
Route::get('notifications', 'ProjectsController@view_notifications');
Route::patch('requests/update_status', 'ProjectsController@update_status');
Route::patch('requests/mark_complete', 'ProjectsController@mark_complete');


//Owner Controller
Route::get('owners', 'OwnersController@show');
Route::post('owners', 'OwnersController@store');
Route::get('owners/{id}/manage', 'OwnersController@view_details');
Route::post('owners/{id}/manage', 'OwnersController@map_user');
Route::get('owners/{id}/edit-lp-id', 'OwnersController@edit_lp_id');
Route::get('owners/{owner_id}/manage/unmap/{user_id}', 'OwnersController@unmap_user');
Route::post('owners/{owner_id}/manage/editMap/{user_id}', 'AjaxController@grant_edit_for_owner');
Route::get('owners/{id}/delete', 'OwnersController@delete');


//Users Controller
Route::get('users', 'UsersController@show');
Route::get('users/create', 'UsersController@add');
Route::post('users', 'UsersController@store');
Route::get('users/{id}/edit', 'UsersController@edit');
Route::patch('users/{id}', 'UsersController@update');
Route::get('users/{id}/delete', 'UsersController@remove');

//Sprints Controller
Route::get('sprints', 'SprintsController@show');
Route::get('sprint/{id}', 'SprintsController@view');
Route::get('sprint/{id}/edit', 'SprintsController@edit');
Route::patch('sprint/{id}', 'SprintsController@update');
Route::get('sprints/create', 'SprintsController@create');
Route::post('sprints', 'SprintsController@store');
Route::patch('sprints/assign_project', 'SprintsController@assign_project');
Route::patch('sprints/deassign_project', 'SprintsController@deassign_project');


//AJAX Controller (misc requests)
Route::post('request/{request_id}/ajax/status', 'AjaxController@getCurrentRequestStatus');
Route::post('user/ajax/notifications', 'AjaxController@getNewNotificationCount');

//Redirect to Signoff
Route::get('signoff', function () { return redirect()->away('http://signoff.pugetsound.edu');});
