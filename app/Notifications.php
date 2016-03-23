<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['notif_user_id', 'notif_project_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
