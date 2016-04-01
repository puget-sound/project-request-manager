<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class EmptyRequest extends Request {

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
    return [];
}
 
}