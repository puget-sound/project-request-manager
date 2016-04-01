<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class ProjectsRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'request_name' => 'required|min:3',
   'project_desc' => 'required',
   'project_owner' => 'required',
   'priority' => 'required',
   'order' => 'required|numeric'
  ];
 }

 public function messages()
{
    return [
        'request_name.required' => 'Project Name field is required.',
        'request_name.min' => 'Project Name must be at least 3 characters long.'
    ];
}
 
}