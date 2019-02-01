<?php 

namespace App\Http\Requests;

use App\Http\Requests\Requests;

class ERPReportCategoryDeleteRequest extends Request {

 public function authorize()
 {
  return true;
 }
 
 public function rules()
 {
  return [
   'category_id' => 'required|integer',
  ];
 }

 public function messages()
{
    return [];
}
 
}
?>