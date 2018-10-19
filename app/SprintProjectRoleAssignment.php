<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Users;
use App\Sprints;
use App\SprintProjectRole;
use App\Projects;

class SprintProjectRoleAssignment extends Model {
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sprint_project_role_assignment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['projects_id, sprint_id, sprint_project_role_id, user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo('App\Users');
    }

    public function sprint()
    {
        return $this->belongsTo('App\Sprints');
    }

    public function project()
    {
        return $this->belongsTo('App\Projects', 'id', 'projects_id');
    }

    public function sprintprojectrole()
    {
        return $this->belongsTo('App\SprintProjectRole');
    }
}
?>