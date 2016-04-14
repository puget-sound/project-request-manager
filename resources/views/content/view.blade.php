@extends('app')

@section('title')
View Project Request
@endsection

@section('content')
@include('modals.project-actions', ['sprints' => $sprints])
@include('modals.project-actions-delete', ['sprints' => $sprints, 'project' => $projects])
@include('errors.list')
<div class="col-md-9" style='padding-left: 0px;'>
<h3 style='margin-top: 10px;'>{{ $projects->request_name }}</h3>
<h4 class='text-muted'>{{ $projects->name }}</h4>
<h4>
	@if ($projects->status == "")
	<span class='label label-default'>STATUS UNKNOWN</span>
	@endif
	@if ($projects->status == "0")
	<span class='label label-primary'>NEEDS REVIEW</span>
	@endif
	@if ($projects->status == "1")
	<span class='label label-default'>PENDING</span>
	@endif
	@if ($projects->status == "2")
	<span class='label label-info'>READY TO SCHEDULE</span>
	@endif
	@if ($projects->status == "3")
	<span class='label label-warning'>SCHEDULED</span>
	@endif
	@if ($projects->status == "4")
	<span class='label label-danger'>REFER TO ORACLE</span>
	@endif
	@if ($projects->status == "5")
	<span class='label label-danger'>DEFERRED</span>
	@endif
	@if ($projects->status == "6")
	<span class='label label-success'>COMPLETED</span>
	@endif
	@if ($projects->status == "7")
	<span class='label label-success' style="background-color: purple;">NEW REQUEST</span>
	@endif
</h4>

<div style='width: 250px; height: 55px; background-color: white;'>
	<div style='float: left; width: 100px; border-radius: 5px; height: 55px; margin-right: 5px; background-color:
	@if ($projects->priority == 0)
		#d9534f
	@elseif ($projects->priority == 1)
		#f0ad4e
	@elseif ($projects->priority == 2)
		#337ab7
	@endif
	'>
		<p style='font-size: 20px; width: 100px; text-align: center; font-weight: bold; color: white; margin-top: 5px; margin-bottom: 0px; text-shadow: 1px 1px #000;'>
	@if ($projects->priority == 0)
		HIGH
	@elseif ($projects->priority == 1)
		MEDIUM
	@elseif ($projects->priority == 2)
		LOW
	@endif</p>
		<p style='font-size: 10px; width: 100px; text-align: center; color: white; text-shadow: 1px 1px #000;'>PRIORITY</p>
	</div>
	<div style='float: left; width: 70px; border-radius: 5px; height: 55px; background-color: gray; margin-right: 5px;'>
		<p style='font-size: 20px; width: 70px; text-align: center; font-weight: bold; color: white; margin-top: 5px; margin-bottom: 0px; text-shadow: 1px 1px #000;'>{{ $projects->order }}</p>
		<p style='font-size: 10px; width: 70px; text-align: center; color: white; text-shadow: 1px 1px #000;'>ORDER</p>
	</div>
	@if ($projects->sprint != NULL)
	<div style='float: left; width: 70px; border-radius: 5px; height: 55px; background-color: #5bc0de'>
		<p style='font-size: 20px; width: 70px; text-align: center; font-weight: bold; color: white; margin-top: 5px; margin-bottom: 0px; text-shadow: 1px 1px #000;'>{{ $projects->sprint }}</p>
		<p style='font-size: 10px; width: 70px; text-align: center; color: white; text-shadow: 1px 1px #000;'>SPRINT</p>
	</div>
	@endif
</div>
<h4 style='margin-top: 40px;'>Project Details</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
@if ($projects->stakeholders != "")<p><strong>Other Stakeholders:</strong> {{ $projects->stakeholders }}</p> @endif
<p><strong>Project in Cascade:</strong> @if ($projects->cascade_flag == 'N') No @else Yes @endif</p>
@if ($projects->project_size != "")<p><strong>Project Size:</strong> {{ $projects->project_size }}</p> @endif
@if ($projects->client_request_month != NULL && $projects->client_request_year != NULL) <p><strong>Requested Completion:</strong> {{ $projects->client_request_month }} {{ $projects->client_request_year }}</p> @endif
@if ($projects->ts_request_month != NULL && $projects->ts_request_year != NULL) <p><strong>TS Scheduled:</strong> <span class='text-success'>{{ $projects->ts_request_month }} {{ $projects->ts_request_year }}</span></p> @endif
<h4 style='margin-top: 30px;'>Project Description</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
<p>{!! nl2br($projects->project_desc) !!}</p>
<h4 style='margin-top: 30px;'>Notes, Comments, and History</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
@foreach($comments as $comment)
<div>
	<p style='margin-bottom: 2px;' class='text-primary'><strong>{{ $comment->fullname }}</strong></p>
	<small><p class='text-muted' style='margin-bottom: 2px;'>{{ $comment->created_at->diffForHumans() }} @if ($user->admin == 1 || $user->id == $comment->comment_user_id) &nbsp;&nbsp;<a href="{{ url('comment/' . $comment->id . '/delete') }}">remove</a> @endif</p></small>
	<p>{!! $comment->comment !!}</p>
</div>
<hr style='margin-top: 10px; margin-bottom: 10px;'>
@endforeach
{!! Form::open(['url' => 'request/' . $projects->id]) !!}
{!! Form::hidden('comment_project_id', $projects->id) !!}
<div class='col-md-10' style='padding-left: 0px; margin-bottom: 10px;'>
	{!! Form::text('comment', null, ['class' => 'form-control', 'placeholder' => 'Add a comment (300 character limit)']) !!}
</div>
<div class='col-md-2' style='padding-left: 0px; margin-bottom: 10px;'>
	{!! Form::submit('Comment', ['class' => 'btn btn-primary form-control', 'style'=>'width: 100%;']) !!}
</div>
{!! Form::close() !!}

</div>


<div class="col-md-3 list-group" style='margin-top: 10px;'>
  <div href="#" class="list-group-item active">
    Actions
  </div>
   @if ($projects->status == 6 || $projects->status == 5)
  	  <p style='padding: 5px; padding-top: 20px;' class='text-muted'><span class='glyphicon glyphicon-lock'></span>&nbsp;This project is currently locked due to it's status set as either <strong>Completed</strong> or <strong>Deferred.</strong> To unlock this project, please contact your TS project representative.</p>
 	@else
 	@if (in_array($projects->id, json_decode(json_encode($my_projects), true)) || $user->admin == 1)<a href="{{ url('request/' . $projects->id . '/edit') }}" class="list-group-item"><span class='glyphicon glyphicon-pencil'></span>&nbsp;&nbsp;Edit Details</a>@endif
	  @if (in_array($projects->id, json_decode(json_encode($my_projects), true)) || $user->admin == 1)<a href="{{ url('request/' . $projects->id . '/reorder') }}" class="list-group-item"><span class='glyphicon glyphicon-transfer'></span>&nbsp;&nbsp;Reorder Project</a>@endif
	  @if ($user->admin == 1)<a class="list-group-item" href="#" data-toggle="modal" data-target="#updateStatus" data-prmid="{{ $projects->id }}" data-prmtype="Update" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-refresh'></span>&nbsp;&nbsp;Update Status</a>@endif
	  @if ($user->admin == 1)<a class="list-group-item" href="#" data-toggle="modal" data-target="#markComplete" data-prmid="{{ $projects->id }}" data-prmtype="Complete" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Mark As Complete</a>@endif
	  @if ($user->admin == 1)<a class="list-group-item" href="#" data-toggle="modal" data-target="#markDeferred" data-prmid="{{ $projects->id }}" data-prmtype="Deferred" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-remove'></span>&nbsp;&nbsp;Mark As Deferred</a>@endif
	  @if ($projects->sprint == "" || $projects->sprint == NULL)
	  	@if ($user->admin == 1)<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintAssign" data-prmid="{{ $projects->id }}" data-prmtype="Assign" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-share-alt'></span>&nbsp;&nbsp;Assign/Reassign to Sprint</a>@endif
	  @else
	  	@if ($user->admin == 1)<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintDeassign" data-prmid="{{ $projects->id }}" data-prmtype="Deferred" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-remove-sign'></span>&nbsp;&nbsp;Deassign from Sprint</a>@endif
	  @endif
	  @if ($user->admin == 1)<a href="#" class="list-group-item" data-toggle="modal" data-target="#deleteProject" data-prmtype="Delete" data-prmval="{{ $projects->request_name }}"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete Project</a> @endif
  @endif

</div>
@endsection
