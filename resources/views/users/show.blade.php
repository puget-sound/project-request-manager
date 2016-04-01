@extends('app')

@section('title')
View System Users
@endsection
@section('content')
	@include('errors.list')
	@include('modals.confirm-delete')
	<div class='row'>
		<div class='col-md-9'>
			<div class='input-group'>
			 	<div class="input-group-addon">Search Users</div><input id="filterUsers" type='text' class='form-control' />
			</div>
		</div>
		<div class='col-md-3'>
			<a href="{{ url('users/create') }}" class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-plus'></span>&nbsp;&nbsp;Add User </a>
		</div>
	</div>
	<table class='table sortable-theme-bootstrap table-striped' data-sortable style='margin-top: 10px;'>
		<thead>
			<th>Full Name</th>
			<th>Username</th>
			<th>Role</th>
			<th style='text-align: center;'>Actions</th>
		</thead>
		<tbody class='users_searchable'>
@foreach($users as $user)
		<tr>
			<td style="vertical-align:middle;">{{ $user->fullname }}</td>
			<td style="vertical-align:middle;">{{ $user->username }}</td>
			<td style="vertical-align:middle;">
				@if($user->admin == 1)
					<span class='label label-success'>Admin</span> 
				@else
					<span class='label label-primary'>User</span>
				@endif
			</td>
			<td style='text-align: center;'>
				<a href='#' disabled class='btn btn-primary disabled'>View Groups</a>
				<a href='#' data-toggle="modal" data-target="#deleteModal" data-prmid="{{ $user->id }}" data-prmtype="User" data-prmval="{{ $user->fullname }}" class='btn btn-danger'><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete User</a>
			</td>
		</tr>
@endforeach
		</tbody>
	</table>
@endsection