@extends('app')

@section('title')
	Projects Assigned to Sprint {{ $sprint->sprintNumber }}
@endsection
@section('content')
	@include('errors.list')
	@include('modals.project-actions', ['sprints' => $sprints])
	<table class="table sortable-theme-bootstrap table-hover" data-sortable style='margin-top: 10px;'>
		<thead>

		<th>Project Name</th>
		<th>Project Owner</th>
		<th>Priority</th>
		<th>Order</th>
		<th>Status</th>
		<th data-sortable="false">Actions</th>
		</thead>
		<tbody class="projects_searchable">
			@foreach($projects as $project)
			<tr @if ($project->inst_priority == 1) class='warning' @endif>
				<td style="vertical-align:middle;">{{ str_limit($project->request_name, $limit = 50, $end = '...') }}</td>
				<td style="vertical-align:middle;">{{ $project->name }}</td>
				<td style="vertical-align:middle;" data-value="{{$project->priority}}"><span class="label @if($project->priority == '0')label-danger"> High @endif @if($project->priority == '1')label-warning"> Medium @endif @if($project->priority == '2')label-primary"> Low @endif</span></td>
				<td style="vertical-align:middle;"><strong>{{ $project->order }}</strong></td>
				<td style="vertical-align: middle;">
					@if ($project->status == "")
					<span class='label label-default'>Unknown</span>
					@endif
					@if ($project->status == "0")
					<span class='label label-primary'>Review</span>
					@endif
					@if ($project->status == "1")
					<span class='label label-warning'>Pending</span>
					@endif
					@if ($project->status == "2")
					<span class='label label-info'>Ready</span>
					@endif
					@if ($project->status == "3")
					<span class='label label-success'>Scheduled</span>
					@endif
					@if ($project->status == "4")
					<span class='label label-danger'>Oracle</span>
					@endif
					@if ($project->status == "5")
					<span class='label label-danger'>Deferred</span>
					@endif
					@if ($project->status == "6")
					<span class='label label-default'>Completed</span>
					@endif
				</td>
				<td style="vertical-align:middle;">
					  <a href='{{ url('request') }}/{{ $project->id }}' class="btn btn-sm btn-primary"><span class='glyphicon glyphicon-eye-open'></span>&nbsp;&nbsp;View</a>
					  @if ($project->status == "6" || $project->status == "5")
					  <a class="btn btn-sm btn-default" disabled href="#"><span class='glyphicon glyphicon-lock'></span>&nbsp;&nbsp;Locked Project</a>
					  @else
					  <a class="btn btn-sm btn-success" href="#" data-toggle="modal" data-target="#markComplete" data-prmid="{{ $project->id }}" data-prmtype="Complete" data-prmval="{{ $project->request_name }}"><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Mark Complete</a>
					  @endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

@endsection
