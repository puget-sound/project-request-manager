<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class AssignmentRequest extends Request {

	public function authorize()
	{
	  return true;
	}
 
	public function rules()
	{
		return [
	    'sprint_id' => 'required',
	    'user_id' => 'required',
	    'sprint_project_role_id' => 'required',
	    'projects_id' => 'required'
	  ];
	}

	public function messages()
	{
	    return [];
	}
}
?>