<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class OwnersRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'name' => 'required|min:3',
  ];
 }


// public function messages()
//{
//}

 
}