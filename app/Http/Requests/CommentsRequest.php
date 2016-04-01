<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class CommentsRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'comment' => 'required',
  ];
 }


// public function messages()
//{
//}

 
}