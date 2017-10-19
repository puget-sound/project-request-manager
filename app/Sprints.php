<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
      ->withTimestamps();
    }

    public function getSprintInfoAttribute()
    {
      return $this->sprintNumber . ' &nbsp;&nbsp;' . $this->sprintStart->format('F j, Y') . ' - ' . $this->sprintEnd->format('F j, Y') . '';
    }
}
