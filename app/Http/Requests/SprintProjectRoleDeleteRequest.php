<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class SprintProjectRoleDeleteRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'role_id' => 'required|integer',
  ];
 }

 public function messages()
{
    return [];
}
 
}
?>