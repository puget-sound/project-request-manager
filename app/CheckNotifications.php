<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckNotifications extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notification_check';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['notif_check_user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
