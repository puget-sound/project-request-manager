@extends('app')
@include('errors.list')
@section('title')
Manage Sprints <small class="new-sprint"><a class="btn btn-primary btn-sm" href="{{ url('sprints/create') }}"><span class='glyphicon glyphicon-plus'></span> New Sprint</a></small>
@endsection
@section('content')
	<div class="row">
		<div class="col-md-8 sprints-list">
		<table class="table sortable-theme-bootstrap" data-sortable>
			<thead>
				<th data-sortable="false">Sprint</th>
				<th data-sortable="false">Status</th>
				<th data-sortable="false">Complete %</th>
				<th data-sortable="false">Start</th>
				<th data-sortable="false">End</th>
				<th></th>
				<!--<th>Actions</th>-->
			</thead>
			<tbody>
				<tr>
					<td colspan="5" class="sprint-header sprint-header-active">Active sprint</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber == $current_sprint)
				<tr class="sprint-list-item">
					<td style="vertical-align:middle;"><strong><a href="{{ url('sprint/' . $sprint->sprintNumber)}}">Sprint {{ $sprint->sprintNumber }}</a></strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-success">Active</span>
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }}%</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td><small class="pull-right sprint-item-edit"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/edit') }}">edit</a></small></td>
					<td><small class="pull-right sprint-item-edit"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/planning')}}">Sprint Planning</a></small></td>
					<!--<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber)}}" class='btn btn-primary btn-sm'><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;View Projects</a></td>-->
				</tr>
					@endif
				@endforeach
				<tr>
					<td colspan="5" class="sprint-header">Future sprints</td>
				</tr>
				@foreach ($sprints as $sprint)
					@if($sprint->sprintNumber > $current_sprint)
				<tr class="sprint-list-item">
					<td style="vertical-align:middle;"><strong><a href="{{ url('sprint/' . $sprint->sprintNumber)}}">Sprint {{ $sprint->sprintNumber }}</a></strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-success label-future">Future</span>
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }}%</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td><small class="pull-right sprint-item-edit"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/edit') }}">edit</a></small></td>
					<td><small class="pull-right sprint-item-edit"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/planning')}}">Sprint Planning</a></small></td>
					<!--<td style="vertical-align:middle;"><a href="{{ url('sprint/' . $sprint->sprintNumber)}}" class='btn btn-primary btn-sm'><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;View Projects</a></td>-->
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
					<td style="vertical-align:middle;"><strong><a href="{{ url('sprint/' . $sprint->sprintNumber)}}">Sprint {{ $sprint->sprintNumber }}</a></strong></td>
					<td style="vertical-align:middle;">
					<span class="label label-default">Past</span>
					</td>
					<td style="vertical-align:middle; text-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $sprint->completed }}%</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintStart->format('F j, Y') }}</td>
					<td style="vertical-align:middle;">{{ $sprint->sprintEnd->format('F j, Y') }}</td>
					<td><small class="pull-right sprint-item-edit"><a href="{{ url('sprint/' . $sprint->sprintNumber . '/planning')}}">Sprint Planning</a></small></td>
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
