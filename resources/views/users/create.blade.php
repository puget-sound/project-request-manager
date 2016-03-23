@extends('app')

@section('title')
Add User
@endsection

@section('content')

{!! Form::open(['url' => 'users']) !!}

	@include('errors.list')
	<div class="form-group">
		{!! Form::label('username', 'Username: ') !!}
		{!! Form::text('username', null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('admin', 'Role: ') !!}
		{!! Form::select('admin', ['0' => 'User',  '1' => 'Administrator'], null, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::submit('Add User', ['class' => 'btn btn-primary form-control']) !!}
	</div>

{!! Form::close() !!}


@endsection