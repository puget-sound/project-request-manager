<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SprintProjectRole extends Model {
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sprint_project_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function sprintprojectroleassignment()
    {
        return $this->hasMany('App\SprintProjectRoleAssignment');
    }

    public function is(App\SprintProjectRole $role)
    {
        return $this->id == $role->id;
    }
}
?>