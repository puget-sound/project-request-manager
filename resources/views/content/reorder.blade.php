@extends('app')
@include('errors.list')
@section('title')
Move {{ $movingProject->request_name }}
@endsection

@section('content')

{!! Form::model($relatedProjects, ['method' => 'PATCH', 'action' => ['ProjectsController@reorder_process', $movingProject->id]]) !!}
<table id='reorder_table' class='table table sortable-theme-bootstrap' data-sortable>
	<thead>
		<th>Project Name</th>
		<th>Project Owner</th>
		<th>Priority</th>
		<th>Order</th>
		<th>Status</th>
		<th data-sortable="false">Actions</th>
	</thead>
	<tbody>
		{!! Form::hidden('movingProjectId', $movingProject->id) !!}
		<tbody class="projects_searchable">
			@foreach($relatedProjects as $project)
			<tr  @if ($project->id != $movingProject->id) class="sort_not_avail" style="background-color: white;" @else style='background-color: white; cursor: move;' class="info" @endif>
				<td style="vertical-align:middle;" data-sortable="false">{{ str_limit($project->request_name, $limit = 50, $end = '...') }}</td>
				<td style="vertical-align:middle;" data-sortable="false">{{ $project->name }}</td>
				<td style="vertical-align:middle ;" data-sortable="false" data-value="{{$project->priority}}"><span class="label @if($project->priority == '0')label-danger"> High @endif @if($project->priority == '1')label-warning"> Medium @endif @if($project->priority == '2')label-primary"> Low @endif</span></td>
				<td style="vertical-align:middle;" data-sortable="false" class="priority"><strong>{!! Form::text('order[' . $project->id . ']', $project->order, ["class" => "priority", "style" => "border:0px; width: 20px; background: 0;"]) !!}</strong></td>
				<td style="vertical-align: middle;" data-sortable="false">
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
					<span class='label label-success' style="background-color: purple">New</span>
					@endif
				</td>
				<td style="vertical-align:middle;" data-sortable="false">
					@if ($project->id == $movingProject->id)<div class='btn btn-primary'>Drag to Move</div>
					@else <div class='btn btn-default'>Not Selected</div>
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</tbody>
</table>
<div class="form-group">
	{!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}


@endsection
