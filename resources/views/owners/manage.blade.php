@extends('app')
@section('top-banner')
@include('errors.list')
@include('settings.google')
@endsection
@section('title')
Manage {{ $owner->name }}
@endsection

@section('content')
<div class='clearfix'>
	<div class="panel panel-default">
	  <div class="panel-body">
			{!! Form::open(array('url' => 'owners/' . $owner->id . '/manage', 'class'=>'form-inline owner-add-member-form')) !!}
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
				<td style='vertical-align:middle;'><a href="{{ url('owners/' . $group_user->owner_id . '/manage/unmap/' . $group_user->user_id) }}" class='text-danger'><span class="glyphicon glyphicon-trash"></span> Remove from Group</a></td>
				</tr>
				@endforeach

			</tbody>
		</table>
</div>
<hr>
<div class="row">
	<div class="col-md-4">
		<h5>LiquidPlanner Client</h5>
		{!! Form::model($owner, ['method' => 'GET', 'action' => ['OwnersController@edit_lp_id', $owner->id], 'class'=>'form-inline']) !!}

		<div class="form-group">
		{!! Form::select('lp_id', $lp_clients, null, ['class' => 'form-control']) !!}
		{!! Form::hidden('owner_id', $owner->id) !!}
		</div>
	{!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
		{!! Form::close() !!}
	</div>
	<div class="col-md-4">

		<h5>Google Drive</h5>
		{!! Form::open(['method' => 'GET', 'action' => ['OwnersController@edit_google_id', $owner->id], 'class' =>'form-inline']) !!}
		<div class="form-group">
		<select id="googleDriveSelect" class="form-control" name="google_id">
		</select>
		{!! Form::hidden('owner_id', $owner->id) !!}
		<button class="btn btn-primary form-control" type="submit">Save</button>
	</div>
</form>
	</div>

</div>
@endsection
@section('extra-scripts')
	<script type="text/javascript">
	var
	google_search_type = "parents",
	apiKey = "{{$GAapiKey}}",
  clientId = "{{$GAclientId}}"
	ownerGoogleId = "{{ $owner->google_id }}",
	google_content = "Google Drives",
	google_scope = "admin";

	</script>
	<script type="text/javascript" src="{{ URL::asset('js/google-drive.js') }}"></script>
<script async defer src="https://apis.google.com/js/api.js"
				onload="this.onload=function(){};handleClientLoad()"
				onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
	@endsection
