<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class ERPCategoryRequest extends Request {

	public function authorize()
	{
	  return true;
	}
 
	public function rules()
	{
		return [
	    'name' => 'required|string',
	  ];
	}

	public function messages()
	{
	    return [];
	}
}
?>