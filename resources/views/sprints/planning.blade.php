@extends('app')
@include('errors.list')
@section('title')
Sprint Planning - Sprint {{$sprint->sprintNumber}}
@endsection

@section('content')
	@if(!$user->isAdmin() && !$user->isDev())
		<p>You are not authorized to view this page. If you believe this is in error, please contact your system administrator.</p>
	@else
	<table class="table sortable-theme-bootstrap table-hover" data-sortable data-show-columns="true">
		<thead>
			<tr>
				<th data-sortable="true" scope="col">Project Number</th>
				<th scope="col">Project Name</th>
				<th scope="col">Priority</th>
				@foreach($roles as $role)
					<th scope="col">{{$role->name}}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>

			@foreach($projects as $project)
				@if($project->project_number != 6)
					<tr>
						<td style="vertical-align:middle;">{{$project->project_number}}</td>
					    <td style="vertical-align:middle;"><a href='{{ url('request') }}/{{ $project->id }}'>{{ str_limit($project->request_name, $limit = 50, $end = '...') }}</a></td>
					    @if($user->isAdmin())
						    @if($project->checkroleassignment(1, $sprint->id)->first())
						    	<td class="assignmentPriority">
						    		{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@changeassignmentpriority']]) !!}
											{!! Form::hidden('projects_id', $project->id)!!}
											{!! Form::hidden('sprint_id', $sprint->id)!!}
											{!! Form::select('priority', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], $project->checkroleassignment(1, $sprint->id)->first()->priority, ['class' => 'form-control input-sm changePrioritySelect']) !!}
									{!! Form::close() !!}
								</td>
						    @else
						    	<td class="newAssignmentPriority assignmentPriority">{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@changeassignmentpriority']]) !!}
										{!! Form::hidden('projects_id', $project->id)!!}
										{!! Form::hidden('sprint_id', $sprint->id)!!}
										{!! Form::select('priority', [0, 1, 2, 3], null, ['class' => 'form-control input-sm changePrioritySelect']) !!}
								{!! Form::close() !!}</td>
						    @endif

					    @else
					    	@if($project->checkroleassignment(1, $sprint->id)->first())
						    	<td>
						    		{{$project->checkroleassignment(1, $sprint->id)->first()->priority}}
								</td>
						    @else
						    	<td></td>
						    @endif
					    @endif

					    @foreach($roles as $role)

					    	@if($project->checkroleassignment($role->id, $sprint->id)->first())
									<td data-value="{{$project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->fullname}}">
						    	@if($user->isAdmin())
				    					{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@assignrole'], 'class' => 'changeAssignmentForm']) !!}
				    						{!! Form::hidden('assignment_id', $project->checkroleassignment($role->id, $sprint->id)->first()->id)!!}
											{!! Form::hidden('sprint_project_role_id', $role->id)!!}
											{!! Form::hidden('projects_id', $project->id)!!}
											{!! Form::hidden('sprint_id', $sprint->id)!!}
											{!! Form::select('user_id', $developers, $project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->id, ['class' => 'form-control input-sm changeAssignmentSelect']) !!}
										{!! Form::close() !!}
				    			@else
				    					{{$project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->fullname}}
				    			@endif
								</td>
					    	@else
					    		@if($user->isAdmin())
						    		<td data-value="ZZZ">
										{!! Form::open(['method' => 'POST', 'action' => ['SprintsController@createassignment'], 'class' => 'newAssignmentForm' ]) !!}
										{!! Form::hidden('projects_id', $project->id)!!}
										{!! Form::hidden('sprint_id', $sprint->id)!!}
										{!! Form::hidden('sprint_project_role_id', $role->id)!!}
										{!! Form::select('user_id', ['0' => 'Select'] + $developers, null, ['class' => 'form-control input-sm newAssignmentSelect']) !!}
									{!! Form::close() !!}
									</td>
								@else
				    				<td>

				    				</td>
				    			@endif
					    	@endif
					    @endforeach
				    </tr>
		    	@endif
			@endforeach
		</tbody>
	</table>
	@endif
@endsection
@section('extra-scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$( "thead th:nth-child(4)" ).trigger('click');
		var assignRoleURL = "{{route('assignrole')}}";
		$(".newAssignmentSelect, .changeAssignmentSelect, .changePrioritySelect").on('change', function() {
			var newAssignmentName = $(this).find("option:selected").text();
			var newAssignmentForm = $(this).parent('form');
			var newAssignmentData = $(newAssignmentForm).serialize();
			var newAssignmentURL = $(newAssignmentForm).attr("action");
			$.ajax({
    		url: newAssignmentURL,
    		type: 'POST',
    		data: newAssignmentData,
				success: function( resp ) {
    			$(newAssignmentForm).parent('td').css('background', '#dff0d8').delay(800).queue(function (next) {
    				$(this).css('background', 'transparent');
    				next();
  				});;
					$('th[data-sorted="true"]').attr('data-sorted', 'false');
					$(newAssignmentForm).parent('td').attr('data-value', newAssignmentName);
					if(resp.role_id === "1") {
						$(newAssignmentForm).parent('td').siblings('.newAssignmentPriority').find('form').show();
					}
					if(resp.planning_response === "new_assignment") {
						$(newAssignmentForm).attr('action', assignRoleURL).attr('method', 'PATCH').append('<input name="_method" type="hidden" value="PATCH">').append('<input name="assignment_id" type="hidden" value="' + resp.assignment_id + '">');
					}
  			}
				});
		});
	});
</script>
@endsection
