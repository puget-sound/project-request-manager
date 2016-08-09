<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'fullname', 'admin', 'active', 'dev'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function isLP()
    {
        return ($this->admin == 1 || $this->dev == 1);
    }
}
