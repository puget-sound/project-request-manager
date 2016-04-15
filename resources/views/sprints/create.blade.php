@extends('app')

@section('title')
Add Sprint
@endsection

@section('content')
	<div class="row">
	  <div class="col-md-6">
{!! Form::open(['url' => 'sprints']) !!}

	@include('errors.list')
	<div class="form-group">
		{!! Form::label('sprintNumber', 'Sprint Number ') !!}
		<div class="input-group">
			  <span class="input-group-addon" id="basic-addon1">Sprint</span>
			{!! Form::text('sprintNumber', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('sprintStart', 'Sprint Start ') !!}
		{!! Form::input('date', 'sprintStart', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('sprintEnd', 'Sprint End ') !!}
		{!! Form::input('date', 'sprintEnd', null, ['class' => 'form-control']) !!}
	</div>
		{!! Form::submit('Add Sprint', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
	<a href="{{ url('sprints') }}">Cancel</a>
	</div>
</div>
@endsection
