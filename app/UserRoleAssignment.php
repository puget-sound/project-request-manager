<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoleAssignment extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = false;

    protected $table = 'user_role_assignment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo('App\Users');
    }

    public function sprint_project_role()
    {
        return $this->belongsTo('App\SprintProjectRole');
    }

    public function role_assignment() 
    {
        return $this->hasMany('App\SprintProjectRoleAssignment');
    }
}
?>