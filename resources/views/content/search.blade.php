@extends('app')

@section('title')
Project Search
@endsection

@section('content')
@include('errors.list')

{!! Form::open(['method' => 'GET', 'action' => ['ProjectsController@process_search']]) !!}
	<div class="form-group">
	{!! Form::label('sq_n', 'Project Name: ') !!}
	{!! Form::text('sq_n', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="form-group">
	{!! Form::label('sq_o', 'Owner: ') !!}
	<select name="sq_o" class="form-control">
		<option value="">Any</option>
		@foreach ($owners as $owner)
		<option value="{{ $owner->id }}">{{ $owner->name }}</option>
		@endforeach
	</select>
	</div>

	<div class="form-group">
	{!! Form::label('sq_s', 'Status: ') !!}
	<div class='form-inline'>
		{!! Form::select('sq_so', ['LIKE' => 'Show', 'NOT LIKE' => 'Hide'], null, ['class' => 'form-control']) !!}
		{!! Form::select('sq_s', ['' => 'Any', '7' => 'New', '0' => 'Needs Review', '1' => 'Pending', '2' => 'Ready to Schedule', '3' => 'Scheduled', '4' => 'Refer to Oracle', '5' => 'Deferred', '6' => 'Completed'], null, ['class' => 'form-control']) !!}
	</div>
	</div>	
	<div class="form-group">
	{!! Form::label('sq_p', 'Project Priority: ') !!}
	{!! Form::select('sq_p', ['' => 'Any', '0' => 'High', '1' => 'Medium', '2' => 'Low'], null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
	{!! Form::label('sq_p', 'ERP Cateogry: ') !!}
	{!! Form::select('sq_ip', ['' => 'Any', '0'=> 'Undetermined', '1' => 'Category 1', '2' => 'Category 2', '3' => 'Category 3', '4' => 'Category 4'], null, ['class' => 'form-control']) !!}
	</div>
	<div class="checkbox">
		<label>
			{!! Form::checkbox('sq_c', 'C', null, null) !!} Project in Cascade
		</label>
	</div>
	<div class="form-group">
	{!! Form::submit("Search", ['class' => 'btn btn-primary form-control']) !!}
	</div>
{!! Form::close() !!}




@endsection