<!DOCTYPE html>
@extends('app')
@include('errors.list')
@section('title')
	Sprint {{ $sprint->sprintNumber }} Project Schedule
@endsection
@section('under-title')
	{{ $sprint->sprintStart->format('F j, Y') }} - {{ $sprint->sprintEnd->format('F j, Y') }}
@endsection
@section('content')
	@include('modals.project-actions-complete')
	@include('modals.project-actions-sprints')
	<table class="table sortable-theme-bootstrap table-hover table-large-text" data-sortable style='margin-top: 10px;'>
		<thead>

		<th>Project</th>
		<th>Description</th>
		<th>Owner</th>
		<th>Status</th>
		</thead>
		<tbody class="projects_searchable">
			@foreach($categories as $category)
				@if($sprintProjects->where('erp_report_category_id', $category->id)->count() > 0)
				<tr><td colspan="4" class="table-section-header">{{$category->name}} Projects</td></tr>
			@endif
			@foreach($sprintProjects as $project)
				@if($project->erp_report_category_id == $category->id)
			<tr>
				<td><a href='{{ url('request') }}/{{ $project->id }}'>{{ str_limit($project->request_name, $limit = 90, $end = '...') }}</a></td>
				<td>{{$project->brief_description}}</td>
				<td><a href="{{ url('projects/' . $project->project_owner )}}">{{ $project->project_owner_name }}</a></td>
				<td>
					{{$project->phaseName}}
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
		$("select[id^='updatePhaseSelect']").on('change', function() {
			var projectId = $(this).attr("id").split("-")[1];
			$("#phaseStatusForm" + projectId).submit();
		});
		$("select[id^='updateStatusSelect']").on('change', function() {
			var projectId = $(this).attr("id").split("-")[1];
			$("#phaseStatusForm" + projectId).submit();
		});
	});
</script>
@endsection
