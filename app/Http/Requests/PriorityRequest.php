<?php

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class PriorityRequest extends Request {

	public function authorize()
	{
	  return true;
	}

	public function rules()
	{
		return [
		//'assignment_id' => 'required',
		'projects_id' => 'required',
		'sprint_id' => 'required',
	    'priority' => 'required'
	  ];
	}

	public function messages()
	{
	    return [];
	}
}
?>
