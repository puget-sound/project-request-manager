<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSprintPhase extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_sprint_phase';

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
    protected $hidden = [];
}
