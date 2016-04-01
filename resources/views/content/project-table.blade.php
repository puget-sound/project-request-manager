<table class="table sortable-theme-bootstrap table-hover" data-sortable data-show-columns="true" style='margin-top: 10px;'>
		<thead>
		<th></th>
		<th data-sortable="true">Project Name</th>
		<th data-sortable="true">Project Owner</th>
		<th data-sortable="true">Priority</th>
		<th data-sortable="true">Order</th>
		<th data-sortable="true">Charter</th>
		<th data-sortable="true">Status</th>
		<th data-sortable="false">Actions</th>
		</thead>
		<tbody class="projects_searchable">
			@foreach($projects as $project)
			<tr> 
				<td style="vertical-align: middle;">	
				@if (in_array($project->id, $notifications))
					<a href='{{ url('flag/' . $project->id) }}' style="color: black" class='flag' csrf='{{ csrf_token() }}'><span class="glyphicon glyphicon-flag"></span></a>
				@else
					<a href='{{ url('flag/' . $project->id) }}' style="color: #ccc" class='flag' csrf='{{ csrf_token() }}'><span class="glyphicon glyphicon-flag"></span></a>
				@endif
				<!--<td style="vertical-align:middle;" @if (in_array($project->id, $notifications))data-value=1 @endif >@if (in_array($project->id, $notifications))<span class='glyphicon glyphicon-flag'></span>@endif</td>-->
				</td>
				<td style="vertical-align:middle;">{{ str_limit($project->request_name, $limit = 50, $end = '...') }}</td>
				<td style="vertical-align:middle;">{{ $project->name }}</td>
				<td style="vertical-align:middle;" data-value="{{$project->priority}}"><span class="label @if($project->priority == '0')label-danger"> High @endif @if($project->priority == '1')label-warning"> Medium @endif @if($project->priority == '2')label-primary"> Low @endif</span></td>
				<td style="vertical-align:middle;"><strong>{{ $project->order }}</strong></td>
				<td style="vertical-align:middle;"><span class="badge">Pending</span></td>
				<td style="vertical-align: middle;">
					@if ($project->status == "")
					<span class='label label-default'>Unknown</span>
					@endif
					@if ($project->status == "0")
					<span class='label label-primary'>Review</span>
					@endif
					@if ($project->status == "1")
					<span class='label label-default'>Pending</span>
					@endif
					@if ($project->status == "2")
					<span class='label label-info'>Ready</span>
					@endif
					@if ($project->status == "3")
					<span class='label label-warning'>Scheduled</span>
					@endif
					@if ($project->status == "4")
					<span class='label label-danger'>Oracle</span>
					@endif
					@if ($project->status == "5")
					<span class='label label-danger'>Deferred</span>
					@endif
					@if ($project->status == "6")
					<span class='label label-success'>Completed</span>
					@endif
					@if ($project->status == "7")
					<span class='label label-success' style="background-color: purple;">New</span>
					@endif
				</td>
				<td style="vertical-align:middle;">
					  <a href='{{ url('request') }}/{{ $project->id }}' class="btn btn-sm btn-primary"><span class='glyphicon glyphicon-eye-open'></span>&nbsp;&nbsp;View</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>