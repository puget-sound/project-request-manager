<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class SprintPhaseDeleteRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'phase_id' => 'required|integer',
  ];
 }

 public function messages()
{
    return [];
}
 
}
?>