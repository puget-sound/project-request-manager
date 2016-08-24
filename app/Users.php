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
    protected $fillable = ['username', 'fullname', 'role', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function isAdmin()
    {
        return $this->role == 2;
    }

    public function isDev()
    {
        return $this->role == 1;
    }

    public function isLP()
    {
        return ($this->role == 1 || $this->role == 2);
    }
}
