<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ERPReportCategory extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'erp_report_category';

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
