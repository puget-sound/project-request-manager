<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class UsersRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'username' => 'required|min:3',
  ];
 }


// public function messages()
//{
//}

 
}