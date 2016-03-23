@extends('app')

@section('title')
Manage Sprints
@endsection
@section('content')
	@include('errors.list')
	<div class='col-md-3 pull-right'>
			<a href="{{ url('sprints/create') }}" class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-plus'></span>&nbsp;&nbsp;New Sprint</a>
	</div>
	<div>
		<table class="table sortable-theme-bootstrap table-striped" data-sortable>
			<thead>
				<th>Sprint</th>
				<th>Status</th>
				<th>Complete %</th>
				<th>Start</th>
				<th>End</th>
				<th>Actions</th>
			</thead>
			<tbody>
				@foreach ($sprints as $sprint)
				<tr>	
					<td style="vertical-align:middle;"><strong>Sprint {{ $sprint->sprintNumber }}</strong></td>
					<td style="vertical-align:middle;">
					@if (\Carbon\Carbon::now() >= $sprint->sprintStart && \Carbon\Carbon::now() <= $sprint->sprintEnd )
					<span class="label label-success">Active</span>
					@endif
					@if (\Carbon\Carbon::now() > $sprint->sprintStart && \Carbon\Carbon::now() > $sprint->sprintEnd )
					<span class="label label-default">Past</span>
					@endif
					@if (\Carbon\Carbon::now() < $sprint->sprintStart)
					<span class="label label-primary">Future</span>
					@endif
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }} %</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber)}}" class='btn btn-primary btn-sm'><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;View Projects</a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection