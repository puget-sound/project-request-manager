<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sprints extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sprints';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sprintNumber', 'sprintStart', 'sprintEnd'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function getDates()
    {
        return ['sprintStart', 'sprintEnd'];
    }

    public function projects()
    {
      return $this->belongsToMany('App\Projects')
      ->withTimestamps()->withPivot('project_sprint_phase_id', 'project_sprint_status_id');
    }

    public function getSprintInfoAttribute()
    {
      return $this->sprintNumber . ' &nbsp;&nbsp;' . $this->sprintStart->format('F j, Y') . ' - ' . $this->sprintEnd->format('F j, Y') . '';
    }
}
