@extends('app')
@include('errors.list')
@section('title')
Edit Project Request
@endsection
@section('content')

	{!! Form::model($project, ['method' => 'PATCH', 'action' => ['ProjectsController@update', $project->id]]) !!}

	@if ($user_details->admin == 1)
		@include('content._projectform', ['submitText' => 'Update Project Request', 'disable' => false])
	@else
		@include('content._projectform', ['submitText' => 'Update Project Request', 'disable' => true])
	@endif
{!! Form::close() !!}
@endsection
