<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class UnlockProjectRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'request_id'=>'required|integer'
  ];
 }

 public function messages()
{
    return [];
}
 
}