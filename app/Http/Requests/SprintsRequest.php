<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class SprintsRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'sprintNumber' => 'required|integer',
  ];
 }

 public function messages()
{
    return [];
}
 
}