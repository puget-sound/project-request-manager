@extends('app')
@include('errors.list')
@include('settings.google')
@section('title')
Sprint Planning - Sprint {{$sprint->sprintNumber}}
@endsection

@section('title-right')
<div class="checkbox">
 <label>
	 <input type="checkbox" id="upOnlyAssigned" @if($user->isDev())checked="true" @endif> Show my assignments only
 </label>
</div>
@endsection
@section('under-title')
	{{ $sprint->sprintStart->format('F j, Y') }} - {{ $sprint->sprintEnd->format('F j, Y') }}
@endsection

@section('content')
	@if(!$user->isAdmin() && !$user->isDev())
		<p>You are not authorized to view this page. If you believe this is in error, please contact your system administrator.</p>
	@else
	<table class="table sortable-theme-bootstrap table-hover google-drive-table" data-sortable data-show-columns="true">
		<thead>
			<tr>
				<th data-sortable="true" scope="col" style="width:75px;">#</th>
				<th scope="col">Project Name</th>
				<th>Phase</th>
				<th scope="col">Priority</th>
				@foreach($roles as $role)
					<th scope="col" @if($role->id == 1)class="up-sort-this" @endif>{{$role->name}}</th>
				@endforeach
        <th scope="col">Folder</th>
			</tr>
		</thead>
		<tbody @if($user->id == 88)class="up-theme-purple" @endif>

			@foreach($projects as $project)
				@if($project->project_number != 6)
					<tr @if($project->check_user_assignments($user->id, $sprint->id)->first())class="up-assigned-row" @endif>
						<td style="vertical-align:middle;" class="google-project-number">{{$project->project_number}}</td>
					    <td style="vertical-align:middle;"><a href='{{ url('request') }}/{{ $project->id }}'>{{ str_limit($project->request_name, $limit = 45, $end = '...') }}</a>
							</td>
							<td style="vertical-align:middle;">
								<small class="text-muted">{{$project->phaseName}}</small>
							</td>
					    @if($user->isAdmin())
						    @if($project->checkroleassignment(1, $sprint->id)->first())
						    	<td class="assignmentPriority" style="vertical-align:middle;">
						    		{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@changeassignmentpriority']]) !!}
											{!! Form::hidden('projects_id', $project->id)!!}
											{!! Form::hidden('sprint_id', $sprint->id)!!}
											{!! Form::select('priority', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], $project->checkroleassignment(1, $sprint->id)->first()->priority, ['class' => 'form-control input-sm changePrioritySelect']) !!}
									{!! Form::close() !!}
								</td>
						    @else
						    	<td class="newAssignmentPriority assignmentPriority" style="vertical-align:middle;">{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@changeassignmentpriority']]) !!}
										{!! Form::hidden('projects_id', $project->id)!!}
										{!! Form::hidden('sprint_id', $sprint->id)!!}
										{!! Form::select('priority', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], null, ['class' => 'form-control input-sm changePrioritySelect']) !!}
								{!! Form::close() !!}</td>
						    @endif

					    @else
					    	@if($project->checkroleassignment(1, $sprint->id)->first())
						    	<td style="vertical-align:middle;text-align:center;min-width:90px;">
						    		{{$project->checkroleassignment(1, $sprint->id)->first()->priority}}
								</td>
						    @else
						    	<td></td>
						    @endif
					    @endif

					    @foreach($roles as $role)

					    	@if($project->checkroleassignment($role->id, $sprint->id)->first())
									<td data-value="{{$project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->fullname}}" style="vertical-align:middle;min-width:120px;" @if($project->is_user_assigned($user->id, $sprint->id, $role->id)->first())class="up-assigned-role" @endif>
						    	@if($user->isAdmin())
				    					{!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@assignrole'], 'class' => 'changeAssignmentForm']) !!}
				    						{!! Form::hidden('assignment_id', $project->checkroleassignment($role->id, $sprint->id)->first()->id)!!}
											{!! Form::hidden('sprint_project_role_id', $role->id)!!}
											{!! Form::hidden('projects_id', $project->id)!!}
											{!! Form::hidden('sprint_id', $sprint->id)!!}
											{!! Form::select('user_id', ['0' => 'not assigned'] + $developers, $project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->id, ['class' => 'form-control input-sm changeAssignmentSelect']) !!}
										{!! Form::close() !!}
				    			@else
				    					{{$project->checkroleassignment($role->id, $sprint->id)->first()->user()->get()->first()->fullname}}
				    			@endif
								</td>
					    	@else
					    		@if($user->isAdmin())
						    		<td data-value="ZZZ" style="vertical-align:middle;">
										{!! Form::open(['method' => 'POST', 'action' => ['SprintsController@createassignment'], 'class' => 'newAssignmentForm' ]) !!}
										{!! Form::hidden('projects_id', $project->id)!!}
										{!! Form::hidden('sprint_id', $sprint->id)!!}
										{!! Form::hidden('sprint_project_role_id', $role->id)!!}
										{!! Form::select('user_id', ['0' => 'not assigned'] + $developers, null, ['class' => 'form-control input-sm newAssignmentSelect']) !!}
									{!! Form::close() !!}
									</td>
								@else
				    				<td data-value="ZZZ">

				    				</td>
				    			@endif
					    	@endif
					    @endforeach
              <td style="vertical-align:middle;"><small><a class="google-folder" href="#" style="display:none;" target="_blank">Folder <span class='glyphicon glyphicon-folder-close'></span></a></small></td>
				    </tr>
		    	@endif
			@endforeach
		</tbody>
	</table>



	@endif
@endsection
@section('extra-scripts')
  <script type="text/javascript">
  var google_search_type = "table",
  apiKey = "{{$GAapiKey}}",
  clientId = "{{$GAclientId}}",
  google_content = "Project Folders";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/google-drive.js') }}"></script>
<script async defer src="https://apis.google.com/js/api.js"
				onload="this.onload=function(){};handleClientLoad()"
				onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
<script type="text/javascript">
	$(document).ready(function() {
		hideShowAssignments()
		setTimeout(function(){ $('th.up-sort-this').click()}, 100);
		$("#upOnlyAssigned").on('change', function() {
			hideShowAssignments()
		});
		var assignRoleURL = "{{route('assignrole')}}";
		var createAssignmentURL = "{{route('createassignment')}}";
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
					if(resp.planning_response === 'deleted_assignment') {
						$(newAssignmentForm).attr('action', createAssignmentURL);
						$(newAssignmentForm).find('input[name="_method"]').remove();
						$(newAssignmentForm).find('input[name="assignment_id"]').remove();
					}
  			}
				});
		});
	});

	function hideShowAssignments() {
		if($("#upOnlyAssigned").is(':checked')) {
			$("tbody tr").hide();
			$("tbody tr.up-assigned-row").show();
		}
		else {
			$("tbody tr").show();
		}
	}
</script>
@endsection
