@extends('app')
@include('errors.list')
@section('title')
View Sprints
@endsection
@section('content')
	<div class="row">
		<div class="col-md-10 sprints-list">
		<table class="table sortable-theme-bootstrap" data-sortable>
			<thead>
				<th data-sortable="false">Sprint</th>
				<th data-sortable="false">Status</th>
				<th data-sortable="false">Start</th>
				<th data-sortable="false">End</th>
				<th></th>
				<th></th>
				<th></th>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="sprint-header sprint-header-active">{{$current_sprint_header}}
					</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber == $current_sprint || ($sprint->sprintNumber == $current_sprint - 1 && $show_last_sprint == "true"))
				<tr class="sprint-list-item">
					<td style="vertical-align:middle;"><strong>Sprint {{ $sprint->sprintNumber }}</strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-success">Active</span>
					</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/project-schedule')}}">Project Schedule</a></td>
					<td style="vertical-align:middle;">
						@if($days_to_sprint_end <= 7 || $sprint->sprintNumber == $current_sprint - 1)
							<a href="{{ url('sprint/' . $sprint->sprintNumber . '/accomplishments')}}">Accomplishments</a>
						@endif
					</td>
					@if($user->isDev() || $user->isAdmin())
						<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/planning')}}">Sprint Assignments</a></td>
					@endif
				</tr>
			@endif
				@endforeach
				<tr>
					<td colspan="5" class="sprint-header">Future sprints</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber > $current_sprint)
				<tr class="sprint-list-item">
					<td style="vertical-align:middle;"><strong>Sprint {{ $sprint->sprintNumber }}</strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-success label-future">Future</span>
					</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/project-schedule')}}">Project Schedule</a></td>
					<td style="vertical-align:middle;"></td>
					@if($user->isDev() || $user->isAdmin())
						<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/planning')}}">Sprint Assignments</a></td>
					@endif
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
				@foreach (collect($sprints)->sortByDesc('sprintNumber') as $sprint)
					@if($sprint->sprintNumber < $current_sprint)
				<tr>
					<td style="vertical-align:middle;"><strong>Sprint {{ $sprint->sprintNumber }}</strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-default">Past</span>
					</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/project-schedule')}}">Project Schedule</a></td>
					<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/accomplishments')}}">Accomplishments</a></td>
					@if($user->isDev() || $user->isAdmin())
						<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/planning')}}">Sprint Assignments</a></td>
					@endif
				</tr>
			@endif
				@endforeach
			</tbody>
		</table>
	</div>
</div>
</div>
@endsection
@section('extra-scripts')
<script type="text/javascript">
    /*$(document).ready(function() {
    	$( ".sprint-list-item" ).hover(function() {
  			$(this).find('.sprint-item-edit').show();
			}, function() {
				$(this).find('.sprint-item-edit').hide();
		});
	});*/
</script>
@endsection
