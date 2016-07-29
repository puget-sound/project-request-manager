@extends('app')
@include('errors.list')
@section('title')
Manage {{ $owner->name }}
@endsection

@section('content')
<div class='clearfix'>
	<div class="panel panel-default">
	  <div class="panel-body">
			{!! Form::open(array('url' => 'owners/', 'class'=>'form-inline owner-add-member-form')) !!}
			{!! Form::hidden('owner_id', $owner->id) !!}
			<div class="form-group">
			{!! Form::label('user_id', 'Add Member') !!}
			{!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
			</div>
			{!! Form::submit('Add to Group', ['class' => 'btn btn-primary form-control']) !!}
			{!! Form::close() !!}
	  </div>
	</div>
	<h3>Group Members</h3>

		<table class='table sortable-theme-bootstrap table-striped' data-sortable>
			<thead>
				<th>Full Name</th>
				<th>Username</th>
				<th>Access Type</th>
				<th>Actions</th>
			</thead>
			<tbody>
				@foreach($group_users as $group_user)
				<tr>
				<td style='vertical-align: middle;'>{{ $group_user->fullname }}</td>
				<td style='vertical-align: middle;'>{{ $group_user->username }}</td>
				<td style='vertical-align: middle;'><input class='manageCheck' href="{{ url('owners/' . $group_user->owner_id . '/manage/editMap/' . $group_user->user_id) }}" type="checkbox" data-toggle="toggle" data-on="Edit" data-off='Read Only' @if ($group_user->edit == 1) checked @endif></td>
				<td><a href="{{ url('owners/' . $group_user->owner_id . '/manage/unmap/' . $group_user->user_id) }}" class='btn btn-danger'>Remove From Group</a></td>
				</tr>
				@endforeach

			</tbody>
		</table>
</div>
<hr>
<div class="row">
	<div class="col-md-6">
		<h5>LiquidPlanner Client</h5>
		<div class="row">
			<div class="col-md-6">
		{!! Form::model($owner, ['method' => 'GET', 'action' => ['OwnersController@edit_lp_id', $owner->id]]) !!}

		<div class="form-group">
		{!! Form::select('lp_id', $lp_clients, null, ['class' => 'form-control']) !!}
		{!! Form::hidden('owner_id', $owner->id) !!}
		</div>
		</div>
		<div class="col-md-3">
	{!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
		{!! Form::close() !!}
	</div>

	</div>
	</div>

</div>
@endsection
