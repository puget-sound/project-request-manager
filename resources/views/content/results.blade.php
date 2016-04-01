@extends('app')

@section('title')
	Search Results
@endsection
@section('content')
	@include('errors.list')
	@if ($query['sterm'] != "") 
	<h4>Searched for "{{ $query['sterm'] }}"</h4>
	@endif
	<p>Project Owner: <strong>{{ $query['owner'] }}</strong>, Status: <strong>{{ $query['so'] }} {{ $query['status'] }}</strong>, Project Priority: <strong>{{ $query['priority'] }}</strong>, ERP Category: <strong>{{ $query['ip'] }}</strong>, Project in Cascsade: <strong>{{ $query['cascade'] }}</strong></p>
	<p class="text-muted">
	@if (count($projects) == 1)
	Returned {{ count($projects) }} result.
	@else
	Returned {{ count($projects) }} results.
	@endif
	</p>
	<a class='btn btn-sm btn-primary' href="{{ URL::previous() }}">Back to Search</a>
	<hr>
	<table class="table sortable-theme-bootstrap table-hover" data-sortable style='margin-top: 10px;'>
		<thead>
		<th>Project Name</th>
		<th>Project Owner</th>
		<th>Priority</th>
		<th>Order</th>
		<th data-sortable="true">ERP Category</th>
		<th data-sortable="true" data-sortable-type="date">Request By</th>
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