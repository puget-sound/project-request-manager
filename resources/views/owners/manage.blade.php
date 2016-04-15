@extends('app')

@section('title')
Manage {{ $owner->name }}
@endsection

@section('content')
@include('errors.list')
{!! Form::open(['url' => 'owners/' . $owner->id . '/manage']) !!}

<div class='clearfix'>
	<h3>Add User to {{ $owner->name }}</h3>
	<div class='col-md-9'>
		{!! Form::hidden('owner_id', $owner->id) !!}
		{!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
	</div>
	<div class='col-md-3'>
		{!! Form::submit('Add to Group', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
{!! Form::close() !!}
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
