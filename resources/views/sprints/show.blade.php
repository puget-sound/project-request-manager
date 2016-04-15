@extends('app')

@section('title')
Manage Sprints <small class="new-sprint"><a class="btn btn-primary btn-sm" href="{{ url('sprints/create') }}"><span class='glyphicon glyphicon-plus'></span> New Sprint</a></small>
@endsection
@section('content')
	@include('errors.list')
	<div class="row">
		<div class="col-md-8 sprints-list">
		<table class="table sortable-theme-bootstrap" data-sortable>
			<thead>
				<th data-sortable="false">Sprint</th>
				<th data-sortable="false">Status</th>
				<th data-sortable="false">Complete %</th>
				<th data-sortable="false">Start</th>
				<th data-sortable="false">End</th>
				<!--<th>Actions</th>-->
			</thead>
			<tbody>
				<tr>
					<td colspan="5" class="sprint-header sprint-header-active">Active sprint</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber == $current_sprint)
				<tr>
					<td style="vertical-align:middle;"><strong><a href="{{ url('sprint/' . $sprint->sprintNumber)}}">Sprint {{ $sprint->sprintNumber }}</a></strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-success">Active</span>
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }} %</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<!--<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber)}}" class='btn btn-primary btn-sm'><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;View Projects</a></td>-->
				</tr>
			@endif
				@endforeach
				<tr>
					<td colspan="5" class="sprint-header">Future sprints</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber > $current_sprint)
				<tr>
					<td style="vertical-align:middle;"><strong><a href="{{ url('sprint/' . $sprint->sprintNumber)}}">Sprint {{ $sprint->sprintNumber }}</a></strong></td>
					<td style="vertical-align:middle;">
					@if (\Carbon\Carbon::now() > $sprint->sprintStart && \Carbon\Carbon::now() > $sprint->sprintEnd )
					<span class="label label-default">Past</span>
					@endif
					@if (\Carbon\Carbon::now() < $sprint->sprintStart)
					<span class="label label-success label-future">Future</span>
					@endif
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }} %</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<!--<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber)}}" class='btn btn-primary btn-sm'><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;View Projects</a></td>-->
				</tr>
			@endif
				@endforeach
			</tbody>
		</table>
		<a data-toggle="collapse" href="#past-sprints" aria-expanded="false" aria-controls="past-sprints" id="view-past-sprints">
  View past sprints
</a>
		<div class="collapse" id="past-sprints">
		<table class="table sortable-theme-bootstrap" data-sortable>
			<tbody>


				<tr>
					<td colspan="5" class="sprint-header sprint-header-past">Past sprints</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber < $current_sprint)
				<tr>
					<td style="vertical-align:middle;"><strong><a href="{{ url('sprint/' . $sprint->sprintNumber)}}">Sprint {{ $sprint->sprintNumber }}</a></strong></td>
					<td style="vertical-align:middle;">
					@if (\Carbon\Carbon::now() > $sprint->sprintStart && \Carbon\Carbon::now() > $sprint->sprintEnd )
					<span class="label label-default">Past</span>
					@endif
					@if (\Carbon\Carbon::now() < $sprint->sprintStart)
					<span class="label label-success label-future">Future</span>
					@endif
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }} %</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
				</tr>
			@endif
				@endforeach
			</tbody>
		</table>
	</div>
</div>
</div>
@endsection
