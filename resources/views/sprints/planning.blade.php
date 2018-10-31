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
				<th scope="col">Assignment Priority</th>
				@foreach($roles as $role)
					<th scope="col">{{$role->name}}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>		
			
			@foreach($projects as $project)
				@if($project->project_number != 6)
					<tr>
						<th scope="row">{{$project->project_number}}</th>
					    <td>{{$project->request_name}}</td>
					    @if($user->isAdmin())
						    @if($project->checkroleassignment(1, $sprint->id)->first())
						    	<td>
						    		{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@changeassignmentpriority'], 'id' => 'priorityForm' . $project->role_id]) !!}
					    						{!! Form::hidden('assignment_id', $project->assignment_id)!!}
												{!! Form::select('priority', [0, 1, 2, 3], $project->checkroleassignment(1, $sprint->id)->first()->priority, ['class' => 'form-control input-sm', 'id'=>'updatePriority-' . $project->checkroleassignment(1, $sprint->id)->first()->id]) !!}
									{!! Form::close() !!}	
								</td>
						    @else
						    	<td></td>
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
						    	@if($user->isAdmin())
							    	<td>
							    		
				    					{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@assignrole'], 'id' => 'assignmentForm' . $project->checkroleassignment($role->id, $sprint->id)->first()->id]) !!}
				    						{!! Form::hidden('assignment_id', $project->checkroleassignment($role->id, $sprint->id)->first()->id)!!}
											{!! Form::hidden('sprint_project_role_id', $role->id)!!}
											{!! Form::hidden('projects_id', $project->id)!!}
											{!! Form::hidden('sprint_id', $sprint->id)!!}
											{!! Form::select('user_id', $developers, $project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->id, ['class' => 'form-control input-sm', 'id'=>'updateAssignment-' . $project->checkroleassignment($role->id, $sprint->id)->first()->id]) !!}
										{!! Form::close() !!}	
					    			</td>
				    			@else
				    				<td>
				    					{{$project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->fullname}}
				    				</td>
				    			@endif
					    	@else
					    		@if($user->isAdmin())
						    		<td>
					    				{!! Form::open(['method' => 'POST', 'url' => 'sprints/createassignment']) !!}
				    						{!! Form::hidden('sprint_project_role_id', $role->id)!!}
				    						{!! Form::hidden('projects_id', $project->id)!!}
				    						{!! Form::hidden('sprint_id', $sprint->id)!!}
				    						{!! Form::hidden('user_id', 12)!!}
											{!! Form::submit('Assign Role', ['class' => 'form-control']) !!}
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
		$("select[id^='updateAssignment']").on('change', function() {
			var assignmentID = $(this).attr("id").split("-")[1];
			$("#assignmentForm" + assignmentID).submit();
		});
		$("select[id^='updatePriority']").on('change', function() {
			var assignmentID = $(this).attr("id").split("-")[1];
			$("#priorityForm" + assignmentID).submit();
		});
	});
</script>
@endsection

