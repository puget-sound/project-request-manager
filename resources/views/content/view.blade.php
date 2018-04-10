@extends('app')
@include('errors.list')
@section('title')
{{ $projects->request_name }}
@endsection
@section('under-title')
<p class="view-project-date">@if($projects->project_number) {{'#'.$projects->project_number}}@endif added <strong>{{$projects->created_at->format('F j, Y')}}</strong></p>
@endsection

@section('content')
@include('modals.project-actions', ['sprints' => $sprints])
@include('modals.project-actions-delete', ['sprints' => $sprints, 'project' => $projects])
@include('modals.project-actions-signoff', ['project' => $projects])
<div class="row">
<div class="col-md-9">
	<div class="row">
		<div class="col-md-7">
<!--<h3 style='margin-top: 10px;'>{{ $projects->request_name }}</h3>-->
<h4 class='view-project-name'><a href="{{ url('projects/' . $projects->project_owner )}}">{{ $projects->name }}</a></h4>
<h4>
	@if ($projects->status == "")
	<span class='label label-default'>Unknown</span>
	@endif
	@if ($projects->status == "0")
	<span class='label label-primary'>Review</span>
	@endif
	@if ($projects->status == "1")
	<span class='label label-warning'>Pending</span>
	@endif
	@if ($projects->status == "2")
	<span class='label label-info'>Ready</span>
	@endif
	@if ($projects->status == "3" && $projects->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber <= $current_sprint)
	<span class='label label-success'>Scheduled</span>
	@endif
	@if ($projects->status == "3" && $projects->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber > $current_sprint)
	<span class='label label-success label-future'>Scheduled</span>
	@endif
	@if ($projects->status == "4")
	<span class='label label-danger'>Oracle</span>
	@endif
	@if ($projects->status == "5")
	<span class='label label-danger'>Deferred</span>
	@endif
	@if ($projects->status == "6")
	<span class='label label-default'>Completed</span>
	@endif
	@if ($projects->status == "7")
	<span class='label label-success' style="background-color: purple;">New</span>
	@endif
</h4>

<div style='height: 55px; background-color: white;'>
	<div style='float: left; width: 100px; border-radius: 5px; height: 55px; margin-right: 5px; background-color:
	@if ($projects->priority == 0)
		#d9534f
	@elseif ($projects->priority == 1)
		#f0ad4e
	@elseif ($projects->priority == 2)
		#337ab7
	@endif
	'>
		<p style='font-size: 20px; width: 100px; text-align: center; font-weight: bold; color: white; margin-top: 5px; margin-bottom: 0px;'>
	@if ($projects->priority == 0)
		HIGH
	@elseif ($projects->priority == 1)
		MEDIUM
	@elseif ($projects->priority == 2)
		LOW
	@endif</p>
		<p style='font-size: 10px; width: 100px; text-align: center; color: white;'>PRIORITY</p>
	</div>
	<div style='float: left; width: 70px; border-radius: 5px; height: 55px; background-color: gray; margin-right: 5px;'>
		<p style='font-size: 20px; width: 70px; text-align: center; font-weight: bold; color: white; margin-top: 5px; margin-bottom: 0px;'>{{ $projects->order }}</p>
		<p style='font-size: 10px; width: 70px; text-align: center; color: white;'>ORDER</p>
	</div>
	@if ($projects->sprints->count() > 0)
	@if ($projects->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber <= $current_sprint)
	<div class="current-sprint" style='float: left; min-width:70px; max-width: 312px; border-radius: 5px; min-height: 55px;'>
	@endif
	@if ($projects->sprints()->orderBy('sprints_id', 'ASC')->first()->sprintNumber > $current_sprint)
	<div class="future-sprint" style='float: left; min-width:70px; max-width: 312px; border-radius: 5px; min-height: 55px;'>
	@endif
	<!--<a href="{{ url('sprint/' . $projects->sprint)}}">-->
		<p style='font-size: 20px; text-align: center; font-weight: bold; margin-top: 5px; margin-bottom: 0px; padding:0 8px;'>
		@if ($user->isAdmin())
			@foreach ($sprint_loop as $key => $value)
    @if( count( $sprint_loop ) != $key + 1 )
        <a href="{{ url('sprint/' . $value)}}">{{ $value }}</a>,
     @else
        <a href="{{ url('sprint/' . $value)}}">{{ $value }}</a>
    @endif
@endforeach
@else
	{{$sprint_display_only}}
@endif
	</p>
		<p style='font-size: 10px; text-align: center; margin:0;'>
			@if( count( $sprint_loop ) > 1 )SPRINTS
			@else SPRINT
			@endif</p>
	<!--</a>-->
	</div>
	@endif
</div>
</div>
<div class="col-md-5">
	@if ($projects->lp_id != "" && $user->isLP())
	<p class="text-right"><a class="btn btn-default btn-sm lp-link" href='https://app.liquidplanner.com/space/{{$lp_workspace}}/projects/show/{{$projects->lp_id}}' target='_blank' role="button">View in LiquidPlanner</a></p>
@endif
@if ($user->isLP())
	<div id="signoffRequestsContainer">
		<h6>Sign-off Requests <small><a href="#">more/less</a></small></h6>
		<div id="signoffRequests"></div>
	</div>
@endif
</div>
</div>
<h4 style='margin-top: 40px;'>Project Details</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
@if ($projects->stakeholders != "")<p><strong>Other Stakeholders:</strong> {{ $projects->stakeholders }}</p> @endif
<p><strong>Project in Cascade:</strong> @if ($projects->cascade_flag == 'N') No @else Yes @endif</p>
@if ($projects->project_size != "")<p><strong>Project Size:</strong> {{ $projects->project_size }}</p> @endif
@if ($projects->client_request_month != NULL && $projects->client_request_year != NULL) <p><strong>Requested Completion:</strong> {{ $projects->client_request_month }} {{ $projects->client_request_year }}</p> @endif
@if ($projects->ts_request_month != NULL && $projects->ts_request_year != NULL) <p><strong>TS Scheduled:</strong> <span class='text-success'>{{ $projects->ts_request_month }} {{ $projects->ts_request_year }}</span></p> @endif
<h4 style='margin-top: 30px;'>Project Description</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
<p>{!! nl2br($projects->project_desc) !!}</p>
@if ($user->isAdmin())
	<h4 style='margin-top: 30px;'>ERP Reporting</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
	@if($projects->hide_from_reports == "1")
		<p><span class="label label-danger">Hide from ERP Reports</span></p>
	@endif
	<p>{!! nl2br($projects->brief_description) !!}</p>
	<p><strong>ERP Reporting Category:</strong> {{$projects->erp_report_category->name}}</p>
@endif
<h4 style='margin-top: 30px;'>Notes, Comments, and History</h4><hr style='margin-top: 10px; margin-bottom: 10px;'>
@foreach($comments as $comment)
<div>
	<p style='margin-bottom: 2px;' class='text-primary'><strong>{{ $comment->fullname }}</strong></p>
	<small><p class='text-muted' style='margin-bottom: 2px;'>{{ $comment->created_at->diffForHumans() }} @if ($user->isAdmin() || $user->id == $comment->comment_user_id) &nbsp;&nbsp;<a href="{{ url('comment/' . $comment->id . '/delete') }}">remove</a> @endif</p></small>
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


<div class="col-md-3 list-group">
   @if ($projects->status == 6 || $projects->status == 5)
  	  <p style='padding: 5px; padding-top: 20px;' class='text-muted'><span class='glyphicon glyphicon-lock'></span>&nbsp;This project is currently locked due to it's status set as either <strong>Completed</strong> or <strong>Deferred.</strong> To unlock this project, please contact your TS project representative.</p>
 	@else
 	@if (in_array($projects->id, json_decode(json_encode($my_projects), true)) || $user->isAdmin() || $user->isDev())
		<div href="#" class="list-group-item active">
			Actions
		</div>
		@endif
		@if (in_array($projects->id, json_decode(json_encode($my_projects), true)) || $user->isAdmin())
		<a href="{{ url('request/' . $projects->id . '/edit') }}" class="list-group-item"><span class='glyphicon glyphicon-pencil'></span>&nbsp;&nbsp;Edit Details</a>
	  <a href="{{ url('request/' . $projects->id . '/reorder') }}" class="list-group-item"><span class='glyphicon glyphicon-sort'></span>&nbsp;&nbsp;Reorder Project</a>@endif
	  @if ($user->isAdmin())<a class="list-group-item" href="#" data-toggle="modal" data-target="#updateStatus" data-prmid="{{ $projects->id }}" data-prmtype="Update" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-refresh'></span>&nbsp;&nbsp;Update Status</a>@endif
	  @if ($user->isAdmin())<a class="list-group-item" href="#" data-toggle="modal" data-target="#markComplete" data-prmid="{{ $projects->id }}" data-prmtype="Complete" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Mark as Complete</a>@endif
	  @if ($user->isAdmin())<a class="list-group-item" href="#" data-toggle="modal" data-target="#markDeferred" data-prmid="{{ $projects->id }}" data-prmtype="Deferred" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-remove'></span>&nbsp;&nbsp;Mark as Deferred</a>@endif
	  @if ($projects->sprints->count() == 0)
	  	@if ($user->isAdmin())<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintAssign" data-prmtype="Add to"><span class='glyphicon glyphicon-plus'></span>&nbsp;&nbsp;Add to Sprint(s)</a>
			@endif
	  @else
	  	@if ($user->isAdmin())
				<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintAssign" data-prmtype="Change"><span class='glyphicon glyphicon-edit'></span>&nbsp;&nbsp;Change Sprint(s)</a>
				<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintDeassign" data-prmid="{{ $projects->id }}" data-prmtype="Deferred" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-remove-sign'></span>&nbsp;&nbsp;Remove from Sprint(s)</a>@endif
	  @endif
	  @if ($user->isAdmin() && $projects->lp_id == "")
			<a class="list-group-item" href="{{ url('request/' . $projects->id . '/send-to-liquidplanner') }}"><span class='glyphicon glyphicon-share'></span>&nbsp;&nbsp;Send to LiquidPlanner</a>
		@endif
		@if ($user->isLP())<a href="#" class="list-group-item" data-toggle="modal" data-target="#newRequestModal" data-prmid="{{ $projects->id }}" data-prmtype="Signoff" data-prmval="{{ $projects->request_name }}"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Create Signoff Request</a>@endif
			@if ($user->isAdmin())
			<a href="#" class="list-group-item" data-toggle="modal" data-target="#deleteProject" data-prmtype="Delete" data-prmval="{{ $projects->request_name }}"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete Project</a> @endif
  @endif
</div>
</div>
@endsection
@section('extra-scripts')
	<script type="text/javascript">
	var project_number = "{{$projects->project_number}}",
	lp_workspace = "{{$lp_workspace}}",
	lp_id = "{{$projects->lp_id}}",
	sprints = "{{$this_sprint_numbers}}",
	project_name = "{{$projects->request_name}}",
	signoff_owner = "{{$projects->signoff_owner}}",
	signoff_api_key = "{{$signoff_api_key}}",
	signoff_base_url = "{{$signoff_base_url}}",
	author = "{{$user->username}}";
	</script>
	<script type="text/javascript" src="{{ URL::asset('js/signoff.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap-multiselect.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        $('#sprint_multiple').multiselect({
            buttonText: function(options, select) {
            	if (options.length === 0) {
                    return (select).data('label');
                }
                else {
                     var labels = [];
                     options.each(function() {
                         if ($(this).attr('label') !== undefined) {
                             labels.push($(this).attr('label'));
                         }
                         else {
                            labels.push($(this).html().split(' &nbsp;&nbsp;')[0]);
                         }
                     });
                     return labels.join(', ') + '';
                 }
            },
            maxHeight: 205,
            buttonClass: 'btn btn-default sq_select_button multiple_sprint_select',
        });
    });
  </script>
@endsection
