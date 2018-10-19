<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class SprintStatusDeleteRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'status_id' => 'required|integer',
  ];
 }

 public function messages()
{
    return [];
}
 
}
?>