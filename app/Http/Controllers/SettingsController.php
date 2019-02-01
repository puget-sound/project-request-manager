<?php
namespace App\Http\Controllers;
use App;
use App\ProjectSprintPhase;
use App\ProjectSprintStatus;
use App\ERPReportCategory;
use App\SprintProjectRole;
use App\Http\Requests\SprintPhaseRequest;
use App\Http\Requests\SprintStatusRequest;
use App\Http\Requests\ERPCategoryRequest;
use App\Http\Requests\SprintProjectRoleRequest;
use App\Http\Requests\SprintStatusDeleteRequest;
use App\Http\Requests\SprintProjectRoleDeleteRequest;
use App\Http\Requests\ERPReportCategoryDeleteRequest;
use App\Http\Requests\SprintPhaseDeleteRequest;
class SettingsController extends Controller{
	
	public function settings()
	{
		$categories = ERPReportCategory::orderBy('id', 'ASC')->get();
		$phases = ProjectSprintPhase::orderBy('id', 'ASC')->get();
		$statuses = ProjectSprintStatus::orderBy('id', 'ASC')->get();
		$roles = SprintProjectRole::orderby('id', 'ASC')->get();
		return view('settings.system', ['categories' => $categories, 'phases' => $phases, 'statuses' => $statuses, 'roles'=> $roles]);
	}

	public function add_erp_category(ERPCategoryRequest $request)
	{
		ERPReportCategory::create($request->all());
		return redirect('settings')->withSuccess("Successfully Created Category");
	}

	public function add_sprint_phase(SprintPhaseRequest $request)
	{
		ProjectSprintPhase::create($request->all());
		return redirect('settings')->withSuccess("Successfully Created Phase");
	}

	public function add_sprint_status(SprintStatusRequest $request)
	{
		ProjectSprintStatus::create($request->all());
		return redirect('settings')->withSuccess("Successfully Created Status");
	}

	public function add_sprint_project_role(SprintProjectRoleRequest $request)
	{
		SprintProjectRole::create($request->all());
		return redirect('settings')->withSuccess("Successfully Created Role");
	}



	public function delete_sprint_status(SprintStatusDeleteRequest $request)
	{
		$status = ProjectSprintStatus::find($request->status_id);
		$status->delete();
		return redirect('settings')->withSuccess("Successfully Deleted Status");
	}

	public function delete_erp_report_category(ERPReportCategoryDeleteRequest $request)
	{
		$category = ERPReportCategory::find($request->category_id);
		$category->delete();
		return redirect('settings')->withSuccess("Successfully Deleted ERP Category");
	}

	public function delete_sprint_project_role(SprintProjectRoleDeleteRequest $request)
	{
		$role = SprintProjectRole::find($request->role_id);
		$role->delete();
		return redirect('settings')->withSuccess("Successfully Deleted Sprint Project Role");
	}

	public function delete_sprint_phase(SprintPhaseDeleteRequest $request)
	{
		$phase = ProjectSprintPhase::find($request->phase_id);
		$phase->delete();
		return redirect('settings')->withSuccess("Successfully Deleted Sprint Phase");
	}
}
?>