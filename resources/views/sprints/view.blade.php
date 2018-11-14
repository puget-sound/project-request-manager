<!DOCTYPE html>
@extends('app')
@include('errors.list')
@section('title')
	Projects Assigned to Sprint {{ $sprint->sprintNumber }}
@endsection
@section('under-title')
	{{ $sprint->sprintStart->format('F j, Y') }} - {{ $sprint->sprintEnd->format('F j, Y') }}
	<h5>{{$projects->count()}} Projects</h5>
@endsection
@section('content')
	@include('modals.project-actions-complete')
	@include('modals.project-actions-sprints')
	<table class="table sortable-theme-bootstrap table-hover" data-sortable style='margin-top: 10px;'>
		<thead>
		<th>Project Name</th>
		<th></th>
		<th>Project Owner</th>
		<th>Sprint Phase</th>
		<th>Sprint Status</th>
		<th>Status</th>
		<th data-sortable="false">Actions</th>
		</thead>
		<tbody class="projects_searchable">
			@foreach($categories as $category)
				@if($projects->where('erp_report_category_id', $category->id)->count() > 0)
				<tr class="table-section-header"><td colspan="7">{{$category->name}} Projects</td></tr>
			@endif
			@foreach($projects->sortBy('phaseName') as $project)
				@if($project->erp_report_category_id == $category->id)
			<tr>

				<td style="vertical-align:middle;"><a href='{{ url('request') }}/{{ $project->id }}'>{{ str_limit($project->request_name, $limit = 50, $end = '...') }}</a></td>
				<td style="vertical-align:middle;">
					@if($project->hide_from_reports == "1")<span class='glyphicon glyphicon-eye-close text-muted'></span>
					@endif
				</td>
				<td style="vertical-align:middle;"><a href="{{ url('projects/' . $project->project_owner )}}">{{ $project->project_owner_name }}</a></td>
				<td>
					{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@set_project_phase_status'], 'id' => 'phaseStatusForm' . $project->id]) !!}
	        			{!! Form::hidden('project_id', $project->id, ['id' => 'project_id_hidden']) !!}
						{!! Form::hidden('sprint_id', $sprint->id, ['id' => 'sprint_id_hidden']) !!}
	        			{!! Form::select('phase_id', array_merge(['0' => 'Please Select'], $sprint_phases), $project->pivot->project_sprint_phase_id, ['class' => 'form-control input-sm', 'id' => 'updatePhaseSelect-' . $project->id]) !!}
				</td>
				<td>
						{!! Form::select('status_id', array_merge(['0' => 'Please Select'], $sprint_statuses), $project->pivot->project_sprint_status_id, ['class' => 'form-control input-sm', 'id' => 'updateStatusSelect-' . $project->id]) !!}
					{!! Form::close() !!}
				</td>
				<!--<td style="vertical-align: middle;">{!! Form::submit('save', ['class' => 'btn btn-link btn-xs pull-right']) !!}

				</td>-->
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
					@if ($project->status == "3" && $project->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber <= $current_sprint)
					<span class='label label-success'>Scheduled</span>
					@endif
					@if ($project->status == "3" && $project->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber > $current_sprint)
					<span class='label label-success label-future'>Scheduled</span>
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
				<td style="vertical-align:middle;" class="sprint-column-small">
					  <!--<a href='{{ url('request') }}/{{ $project->id }}' class="btn btn-sm btn-primary"><span class='glyphicon glyphicon-eye-open'></span>&nbsp;&nbsp;View</a>-->
					  @if ($project->status == "6" || $project->status == "5")
					  <a class="btn btn-sm btn-default" disabled href="#"><span class='glyphicon glyphicon-lock'></span>&nbsp;&nbsp;Locked Project</a>
					  @else
							@if ($project->sprints()->orderBy('sprints_id', 'DESC')->first()->sprintNumber > $sprint->sprintNumber)
								<a class="btn btn-sm btn-default" disabled href="#" id="sprints-list">Sprints {{$project->sprints_display}}</a>
							@else
								<div class="btn-group">
									<a role="button" class="btn btn-xs btn-default" href="#" data-toggle="modal" data-target="#markComplete" data-prmid="{{ $project->id }}" data-prmtype="Complete" data-prmval="{{ $project->request_name }}"><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Mark complete</a><button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" data-toggle="modal" data-target="#sprintExtend" data-prmid="{{ $project->id }}" data-prmval="{{ $project->request_name }}"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Extend to next sprint</a></li>
										<!--<li><a href="#" data-toggle="modal" data-target="#sprintMove" data-prmid="{{ $project->id }}" data-prmval="{{ $project->request_name }}"><span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;Move to next sprint</a></li>-->
									</ul>
								</div>
							@endif

					  @endif
				</td>
			</tr>
		@endif
	@endforeach
			@endforeach
		</tbody>
	</table>

@endsection
@section('extra-scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$("select[id^='updatePhaseSelect'], select[id^='updateStatusSelect']").on('change', function() {
			var projectId = $(this).attr("id").split("-")[1];
			var updateForm = $("#phaseStatusForm" + projectId);
			var statusData = $(updateForm).serialize();

			var updateURL = $(updateForm).attr("action");
			console.log(statusData, updateURL);
			$.ajax({
    		url: updateURL,
    		type: 'POST',
    		data: statusData,
				success: function( resp ) {
    			$(updateForm).parent('td').parent('tr').css('background', '#dff0d8').delay(800).queue(function (next) {
    				$(this).css('background', 'transparent');
    				next();
  				});;
  			}
			});
			//$("#phaseStatusForm" + projectId).submit();
		});
		/*$("select[id^='updateStatusSelect']").on('change', function() {
			var projectId = $(this).attr("id").split("-")[1];
			var statusData = $("#phaseStatusForm" + projectId).serialize();
			console.log(statusData);
			$("#phaseStatusForm" + projectId).submit();
		});*/
	});
</script>
@endsection
