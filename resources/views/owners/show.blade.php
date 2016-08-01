@extends('app')
@include('errors.list')
@section('title')
Project Owners
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
{!! Form::open(['url' => 'owners', 'class'=>'form-inline owner-add-owner-form']) !!}
<div class="form-group">
	{!! Form::label('name', 'Add Owner ') !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Owner Name']) !!}
</div>
<div class="form-group">
	{!! Form::submit('Add Project Owner', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
</div>
</div>
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
				<a href='{{url('owners/' . $owner->id . '/manage')}}' class='btn btn-default lp-link'><span class="glyphicon glyphicon-user"></span> Manage</a>
				<a href='{{url('owners/' . $owner->id . '/delete')}}' class='text-danger delete-owner'><span class="glyphicon glyphicon-trash"></span> Delete</a>
			</td>
		</tr>
@endforeach
		</tbody>
	</table>
</div>
</div>
@endsection
