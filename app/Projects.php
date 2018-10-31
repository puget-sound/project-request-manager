<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SprintProjectRoleAssignment;

class Projects extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use SoftDeletes;

    protected $table = 'requests';

    protected $dates =['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['request_name', 'project_owner', 'project_desc', 'stakeholders', 'priority', 'order', 'cascade_flag', 'project_size', 'inst_priority', 'client_request_month', 'client_request_year', 'ts_request_month', 'ts_request_year', 'status', 'project_number', 'brief_description', 'hide_from_reports', 'erp_report_category_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

     public function sprints()
     {
       return $this->belongsToMany('App\Sprints')
      ->withTimestamps()->withPivot('project_sprint_phase_id', 'project_sprint_status_id');
     }

     public function erp_report_category()
     {
       return $this->belongsTo('App\ERPReportCategory');
     }

     public function project_owner()
     {
        return $this->belongsTo('app\Owner');
     }

     public function sprintprojectroleassignments()
     {
        return $this->hasMany('App\SprintProjectRoleAssignment', 'projects_id');
     }

     public function checkroleassignment($roleid, $sprintid)
     {
        return $this->sprintprojectroleassignments()->where('sprint_project_role_id', "=", $roleid)->where('sprint_id', '=', $sprintid);
     }
}
?>