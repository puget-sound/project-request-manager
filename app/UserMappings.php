<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMappings extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_mappings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['owner_id', 'user_id', 'edit'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
