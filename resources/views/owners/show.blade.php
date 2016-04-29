@extends('app')

@section('title')
Project Owners
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
{!! Form::open(['url' => 'owners']) !!}
@include('errors.list')
<div class="form-group">
	{!! Form::label('name', 'Owner Name ') !!}
	{!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
	{!! Form::submit('Add Project Owner', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
	<table class='table sortable-theme-bootstrap table-striped' data-sortable>
		<thead>
			<th>Owner Name</th>
			<th>Actions</th>
		</thead>
		<tbody>
@foreach($owners as $owner)
		<tr>
			<td style="vertical-align:middle;">{{ $owner->name }}</td>
			<td>
				<a href='{{url('owners/' . $owner->id . '/manage')}}' class='btn btn-warning'><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Manage</a>
				<a href='{{url('owners/' . $owner->id . '/delete')}}' class='btn btn-danger'><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete</a>
			</td>
		</tr>
@endforeach
		</tbody>
	</table>
</div>
</div>
@endsection
