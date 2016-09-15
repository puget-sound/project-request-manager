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
		{!! Form::label('role', 'Role') !!}
		{!! Form::select('role', ['0' => 'User',  '1' => 'Developer', '2' => 'Adminstrator'], null, ['class' => 'form-control']) !!}
	</div>
	<br>
	<div class="form-group">
		{!! Form::submit('Add User', ['class' => 'btn btn-primary form-control']) !!}
	</div>

{!! Form::close() !!}
<a href="{{ url('users') }}">Cancel</a>
</div>
</div>

@endsection
