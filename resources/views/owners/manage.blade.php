@extends('app')
@include('errors.list')
@section('title')
Manage {{ $owner->name }}
@endsection

@section('content')

{!! Form::open(['url' => 'owners/' . $owner->id . '/manage']) !!}

	<div class="row">
		<div class="col-md-6">
			<h5>Add User to {{ $owner->name }}</h5>
			<div class="row">
	<div class='col-md-6'>
		{!! Form::hidden('owner_id', $owner->id) !!}
		{!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
	</div>
	<div class='col-md-4'>
		{!! Form::submit('Add to Group', ['class' => 'btn btn-primary form-control']) !!}
		{!! Form::close() !!}
	</div>
</div>
</div>
<div class="col-md-6">
	<h5>LiquidPlanner ID</h5>
	<div class="row">
		<div class="col-md-4">
	{!! Form::model($owner, ['method' => 'GET', 'action' => ['OwnersController@edit_lp_id', $owner->id]]) !!}
	<div class="form-group">
		{!! Form::text('lp_id', $owner->lp_id, ['class' => 'form-control']) !!}
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


<div class='clearfix'>
	<h3>{{ $owner->name }} Group Members</h3>
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
@endsection
