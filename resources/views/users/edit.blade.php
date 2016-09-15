@extends('app')
@include('errors.list')
@section('title')
Edit User
@endsection

@section('content')
<div class="row">
	<div class="col-md-4">
		<h3>{{$user->fullname}} ({{$user->username}})</h3>
{!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@update', $user->id], 'id'=> 'userEdit']) !!}
{!! Form::hidden('username', $user->username) !!}
	<div class="form-group">
		{!! Form::label('role', 'Role') !!}
		{!! Form::select('role', ['0' => 'User', '1' => 'Developer', '2' => 'Administrator'], null, ['class' => 'form-control']) !!}
	</div>
	<br>
	<div class="form-group">
		{!! Form::submit('Save User', ['class' => 'btn btn-primary']) !!}
	</div>

{!! Form::close() !!}
<a href="{{ url('users') }}">Cancel</a>
</div>
</div>

@endsection
