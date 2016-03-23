<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class ReorderRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [];
 }

 public function messages()
{
    return [
        'request_name.required' => 'Project Name field is required.',
        'request_name.min' => 'Project Name must be at least 3 characters long.'
    ];
}
 
}