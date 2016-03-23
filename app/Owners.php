<?php
//comment
namespace App;

use Illuminate\Database\Eloquent\Model;

class Owners extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_owners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
