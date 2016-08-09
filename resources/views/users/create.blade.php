@extends('app')
@include('errors.list')
@section('title')
Add User
@endsection

@section('content')
<div class="row">
	<div class="col-md-4">
{!! Form::open(['url' => 'users']) !!}

	<div class="form-group">
		{!! Form::label('username', 'Username') !!}
		{!! Form::text('username', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('admin', 'Role') !!}
		{!! Form::select('admin', ['0' => 'User',  '1' => 'Administrator'], null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('dev', 'TS Developer') !!}
		{!! Form::select('dev', ['0' => 'No',  '1' => 'Yes'], null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::submit('Add User', ['class' => 'btn btn-primary form-control']) !!}
	</div>

{!! Form::close() !!}
</div>
</div>

@endsection
