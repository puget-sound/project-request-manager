@extends('app')

@section('title')
Add Sprint
@endsection

@section('content')

{!! Form::open(['url' => 'sprints']) !!}

	@include('errors.list')
	<div class="form-group">
		{!! Form::label('sprintNumber', 'Sprint Number: ') !!}
		<div class="input-group">
			  <span class="input-group-addon" id="basic-addon1">Sprint</span>
			{!! Form::text('sprintNumber', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('sprintStart', 'Sprint Start: ') !!}
		{!! Form::input('date', 'sprintStart', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('sprintEnd', 'Sprint End: ') !!}
		{!! Form::input('date', 'sprintEnd', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::submit('Add Sprint', ['class' => 'btn btn-primary form-control']) !!}
	</div>

{!! Form::close() !!}


@endsection