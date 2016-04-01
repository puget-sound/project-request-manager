<div class="form-group">
	{!! Form::label('request_name', 'Project Name: ') !!}
	@if ($disable)
	{!! Form::text('request_name', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
	@else
	{!! Form::text('request_name', null, ['class' => 'form-control']) !!}
	@endif
</div>
<div class="form-group">
	{!! Form::label('project_desc', 'Description: ') !!}
	{!! Form::textarea('project_desc', null, ['class' => 'form-control', 'id' => 'editarea']) !!}
</div>
<div class="row">
<div class="col-md-4 form-group">
	{!! Form::label('project_owner', 'Project Owner: ') !!}
	@if ($disable)
	{!! Form::hidden('project_owner', null, ['class' => 'form-control']) !!}
	{!! Form::select('project_owner', $owners, null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
	@else
	{!! Form::select('project_owner', $owners, null, ['class' => 'form-control']) !!}
	@endif
</div>
<div class="col-md-4 form-group">
	{!! Form::label('stakeholders', 'Other Stakeholders: ') !!}
	{!! Form::text('stakeholders', null, ['class' => 'form-control', 'placeholder' => 'Department 1, Department 2']) !!}
</div>
<div class="col-md-4 form-group">
	{!! Form::label('priority', 'Priority/Order: ') !!}
	<div class="form-inline">
	{!! Form::select('priority', ['High', 'Medium', 'Low'], null, ['class' => 'form-control']) !!}
	{!! Form::text('order', null, ['class' => 'form-control', 'placeholder' => '1']) !!}
	</div>
</div>
<div class="col-md-4 form-group">
	{!! Form::label('cascade_flag', 'Project in Cascade? ') !!}
	{!! Form::select('cascade_flag', ['N' => 'No', 'C' => 'Yes'], null, ['class' => 'form-control']) !!}
</div>
<div class="col-md-4 form-group">
	{!! Form::label('project_size', 'Project Size: ') !!}
	{!! Form::text('project_size', null, ['class' => 'form-control', 'placeholder' => 'Small/Medium/Large']) !!}
</div>
<div class="col-md-4 form-group">
	{!! Form::label('client_request_month', 'Requested Completion Date: ') !!}
	<div class="form-inline">
	{!! Form::select('client_request_month', ["" => 'Month', 'January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April', 'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December'], null, ['class' => 'form-control']) !!}
	{!! Form::select('client_request_year', ["" => 'Year', '2015' => '2015', '2016' => '2016', '2017' => '2017', '2018' => '2018', '2019' => '2019', '2020' => '2020'], null, ['class' => 'form-control']) !!}
	</div>
</div>
</div>
@if ($disable)
	{!! Form::hidden('TSexpterm', null, ['class' => 'form-control']) !!}
	{!! Form::hidden('TSexpyear', null, ['class' => 'form-control']) !!}
	{!! Form::hidden('status', null, ['class' => 'form-control']) !!}
	{!! Form::hidden('inst_priority', null, ['class' => 'form-control']) !!}
@else
<div class="row">
	<div class="col-md-4 form-group">
	{!! Form::label('ts_request_month', 'TS Estimated Completion Date: ') !!}
	<div class="form-inline">
	{!! Form::select('ts_request_month', ["" => 'Month', 'January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April', 'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December'], null, ['class' => 'form-control']) !!}
	{!! Form::select('ts_request_year', ["" => 'Year', '2015' => '2015', '2016' => '2016', '2017' => '2017', '2018' => '2018', '2019' => '2019', '2020' => '2020'], null, ['class' => 'form-control']) !!}
	</div>
</div>
	<div class="col-md-4 form-group">
		{!! Form::label('status', 'Project Status: ') !!}
		{!! Form::select('status', ['7'=>'New', '0'=>'Needs Review', '1' => 'Pending', '2' => 'Ready to Schedule', '3' => 'Scheduled', '4' => 'Refer to Oracle', '5' => 'Deferred', '6' => 'Completed'], null, ['class' => 'form-control']) !!}
	</div>
	<div class="col-md-4 form-group">
		{!! Form::label('inst_priority', 'ERP Category: ') !!}
		{!! Form::select('inst_priority', ['0' => 'Undetermined', '1' => 'Category 1', '2' => 'Category 2', '3' => 'Category 3', '4' => 'Category 4',], null, ['class' => 'form-control']) !!}
	</div>
</div>
@endif
<div class="form-group">
	{!! Form::submit($submitText, ['class' => 'btn btn-primary form-control']) !!}
</div>

