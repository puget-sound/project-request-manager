@extends('app')

@section('title')
	@if (Request::segment(1) == ('projects'))
		View {{ $owner->name }} Projects
	@elseif (Request::segment(2) == ('all'))
		View All Projects
	@else
		View My Projects
	@endif
@endsection

@section('content')
@include('errors.list')
	<div class='row'>
		<div class='col-md-9'>
			<div class='input-group'>
			 	<div class="input-group-addon">Filter Projects ({{count($projects)}})</div><input id='filterField' type='text' class='form-control' />
			</div>
		</div>
		<div class='col-md-3'>
			 @if ($user->admin == 1) <a href="{{ url('requests/create') }}" class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-plus'></span>&nbsp;&nbsp;Create New Request </a> @endif
		</div>
	</div>
	<table class="table sortable-theme-bootstrap table-hover" data-sortable data-show-columns="true" style='margin-top: 10px;'>
		<thead>
		<th></th>
		<th data-sortable="true">Project Name</th>
		<th data-sortable="true">Project Owner</th>
		<th data-sortable="true">Priority</th>
		<th data-sortable="true">Order</th>
		<th data-sortable="true">ERP Category</th>
		<th data-sortable="true" data-sortable-type="date">Request By</th>
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
				<td style="vertical-align:middle;">
					@if ($project->inst_priority == 0 || $project->inst_priority == NULL) 
						<span class="badge" style='font-size: 10.5px;'>Undetermined</span> 
					@else <span class="badge" style='font-size: 10.5px; background-color: maroon;' title=
						@if ($project->inst_priority == 1)
							"These are projects that must be done - Security Patches, Required Maintenance, Critical items located in legacy, end-of-life systems in imminent danger of failure."
						@endif
						@if ($project->inst_priority == 2)
							"These are projects of critical strategic importance to the university - Institutional priorities as identified by cabinet, Issues involving significant university revenue, Fixes to enhance user experience or address processes that involve a great deal of manual effort."
						@endif
						@if ($project->inst_priority == 3)
							"These are items that are either - Agreed upon voluntary changes in existing business processes that require system work or agreed upon changes in existing systems that require system work. Also includes fixes that improve services or save manual work that is irregular."
						@endif
						@if ($project->inst_priority == 4)
							"This category would include projects that bring entirely new functionality to the university and are not otherwise a legal or regulatory requirement."
						@endif
					data-toggle="tooltip" data-placement="top">Category {{ $project->inst_priority }} </span>
					@endif
				</td>
				@if ($project->client_request_month == NULL && $project->client_request_year == NULL)
				<td style="vertical-align: middle;" data-value="{{ strtotime('1 October 2999') }}"><em class="text-muted">pending</em></td>
				@else
				<td style="vertical-align: middle;" data-value="{{ strtotime('1 ' . $project->client_request_month . ' ' . $project->client_request_year) }}">{{ $project->client_request_month }} {{ $project->client_request_year }}</td>
				@endif

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
@endsection