@extends('app')
@include('errors.list')
@section('title')
System Users <small class="new-system-user"><a class="btn btn-primary btn-sm" href="{{ url('users/create')  }}"><span class='glyphicon glyphicon-plus'></span> New User</a></small>
@endsection
@section('content')
	@include('modals.confirm-delete')
	<div class='row'>
		<div class='col-md-6'>
			<div class='input-group'>
			 	<div class="input-group-addon">Search Users</div><input id="filterUsers" type='text' class='form-control' />
			</div>
	<table class='table sortable-theme-bootstrap table-striped' data-sortable style='margin-top: 10px;'>
		<thead>
			<th>Full Name</th>
			<th>Username</th>
			<th>Role</th>
			<th>Actions</th>
		</thead>
		<tbody class='users_searchable'>
@foreach($users as $user)
		<tr>
			<td style="vertical-align:middle;">{{ $user->fullname }}</td>
			<td style="vertical-align:middle;">{{ $user->username }}</td>
			<td style="vertical-align:middle;">
				@if($user->isAdmin())
					<span class='label label-success'>Admin</span>
				@elseif($user->isDev())
					<span class='label label-info'>Dev</span>
				@else
					<span class='label label-primary'>User</span>
				@endif
			</td>
			<td style="vertical-align:middle;">
				<!--<a href='#' disabled class='btn btn-primary disabled'>View Groups</a>-->
				<small><a href="{{ url('users/' . $user->id . '/edit') }}">edit</a></small>
				<small><a href='#' data-toggle="modal" data-target="#deleteModal" data-prmid="{{ $user->id }}" data-prmtype="User" data-prmval="{{ $user->fullname }}" class='text-danger pull-right'><span class="glyphicon glyphicon-trash"></span> delete</a></small>
			</td>
		</tr>
@endforeach
		</tbody>
	</table>
</div>
</div>
@endsection
