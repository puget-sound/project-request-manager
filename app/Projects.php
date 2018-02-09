<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use SoftDeletes;

    protected $table = 'requests';

    protected $dates =['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['request_name', 'project_owner', 'project_desc', 'stakeholders', 'priority', 'order', 'cascade_flag', 'project_size', 'inst_priority', 'client_request_month', 'client_request_year', 'ts_request_month', 'ts_request_year', 'status', 'brief_description', 'hide_from_reports', 'erp_report_category_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

     public function sprints()
     {
       return $this->belongsToMany('App\Sprints')
      ->withTimestamps();
     }

     public function erp_report_category()
     {
       return $this->belongsTo('App\ERPReportCategory');
     }
}
