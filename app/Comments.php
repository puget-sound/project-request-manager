<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment_user_id', 'comment_project_id', 'comment'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
