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
		<div class="col-md-6">
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
	@if (($projects->status == "3" && $projects->sprint == $current_sprint) || ($projects->status == "3" && $projects->sprint < $current_sprint))
	<span class='label label-success'>Scheduled</span>
	@endif
	@if ($projects->status == "3" && $projects->sprint > $current_sprint)
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
	@if ($projects->sprint != NULL)
	@if ($projects->sprint == $current_sprint || $projects->sprint < $current_sprint)
	<div class="current-sprint" style='float: left; width: 70px; border-radius: 5px; height: 55px;'>
	@endif
	@if ($projects->sprint > $current_sprint)
	<div class="future-sprint" style='float: left; width: 70px; border-radius: 5px; height: 55px;'>
	@endif
	<a href="{{ url('sprint/' . $projects->sprint)}}">
		<p style='font-size: 20px; width: 70px; text-align: center; font-weight: bold; margin-top: 5px; margin-bottom: 0px;'>{{ $projects->sprint }}</p>
		<p style='font-size: 10px; width: 70px; text-align: center;'>SPRINT</p>
	</a>
	</div>
	@endif
</div>
</div>
<div class="col-md-5 col-md-offset-1">
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
	  @if ($projects->sprint == "" || $projects->sprint == NULL)
	  	@if ($user->isAdmin())<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintAssign" data-prmtype="Add to"><span class='glyphicon glyphicon-plus'></span>&nbsp;&nbsp;Add to Sprint</a>
			@endif
	  @else
	  	@if ($user->isAdmin())
				<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintAssign" data-prmtype="Change"><span class='glyphicon glyphicon-edit'></span>&nbsp;&nbsp;Change Sprint</a>
				<a class="list-group-item" href="#" data-toggle="modal" data-target="#sprintDeassign" data-prmid="{{ $projects->id }}" data-prmtype="Deferred" data-prmval="{{ $projects->request_name }}"><span class='glyphicon glyphicon-remove-sign'></span>&nbsp;&nbsp;Remove from Sprint</a>@endif
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
	function clearRequestForm() {
		$("#errorValidate").hide();
		$("#ticketNumberGroup").hide();
		$("#soundNetGroup").show();
		$("#hidedetailfields").show();
		$("#testinggroup").show();
		$("#sumWorkGroup").show();
		$("#typeOfWork").val("project");
		$("#typeOfWork").selectpicker("refresh");
		$("#ticketNumber").val("");
		$("#projectNumber").val("{{$projects->project_number}}");
		$("#soundNetLink").val("");
		$("#lpProjectLink").val("");
		@if ($projects->lp_id != "")
		$("#lpProjectLink").val("https://app.liquidplanner.com/space/{{$lp_workspace}}/projects/show/{{$projects->lp_id}}");
	@endif
		$("#sprintNumber").val("{{$projects->sprint}}");
		$("#projectName").val("{{$projects->request_name}}");
		$("#appDesignerProjects").val("");
		$("#plsqlObjects").val("");
		$("#otherObjects").val("");
		$(".request-representative").val("");
		$("#projectOwnerSelect").val("{{$projects->signoff_owner}}");
		$("#projectOwnerSelect").selectpicker("refresh");
		$("#summaryWorkCompleted").val("");
		$("#testingTypeSelect").val(1);
		$("#testingTypeSelect").selectpicker("refresh");
		var requestLength = $('.request-representative-div').length;
		for (var i = requestLength; i > 1;  i--) {
				$('.request-representative-div:last').remove();
		}
	}
	function validateRequest() {
		var users = $("input[id='requestUsers[]']").map(function(){return $(this).val();}).get();
		if ($("#typeOfWork").val() == "project" && $("#projectNumber").val() == "") {
			$("#errorValidate").html("You must specify a <strong>Project ID</strong>. This field is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		}
		if ($("#typeOfWork").val() == "req" && $("#projectNumber").val() == "") {
			$("#errorValidate").html("You must specify an associated <strong>Project ID</strong> with this request. This field is required.");
		}
		if ($("#typeOfWork").val() == "ticket" && $("#ticketNumber").val() == "") {
			$("#errorValidate").html("You must specify a <strong>KACE Ticket number</strong>. This field is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		}

		if ($("#typeOfWork").val() == "") {
			$("#errorValidate").html("You must specify what kind of <strong>Work</strong> this is. This step is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		} else if ($("#projectName").val() == "") {
			$("#errorValidate").html("You are missing a <strong>Project Name</strong>. This field is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		} else if ($("#projectOwnerSelect").val() == "") {
			$("#errorValidate").html("You need to select a <strong>Project Owner</strong>. This selection is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		} else if ($("#testingTypeSelect").val() == "" && $("#typeOfWork".val() != "req")) {
			$("#errorValidate").html("You need to select a <strong>Proof-of-Testing Type</strong>. This selection is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		}  else if (users.join() == "") {
			$("#errorValidate").html("You need to request sign-off from at least <strong>one user</strong>. This step is required.");
			window.scrollTo(0, 0);
			$("#errorValidate").show();
			return false;
		} else {
			$("#errorValidate").hide();
			return true;
		}
	}
	function getRequests(isNew) {
		var html = "";
		$.getJSON("http://signoff.pugetsound.edu/php/getRequestsByProjectId.php?callback=?", {
			projectId: "{{$projects->project_number}}",
			apiToken: "{{$signoff_api_key}}",
		}, function(data) {
			if (data.hasOwnProperty("error")) {
				$('#signoffRequestsContainer').hide();
			} else {
				if(data.length == 0) {
					$('#signoffRequestsContainer').hide();
				}
				else {
					$('#signoffRequestsContainer').show();
				}
				if(data.length > 2) {
					$('#signoffRequestsContainer h6 small').show();
				}
				$.each(data, function (key, val) {
					var requestClass = "";
					if(key > 1) {
						requestClass = " hiddenRequest";
					}
					if (val.status == "Pending") {
		    		val.status = "<span class='label label-primary signoff-status'>" + val.status + "</span>";
		    	}
		    	if (val.status == "Received") {
		    		val.status = "<span class='label label-success signoff-status'>" + val.status + "</span>";
		    	}
		    	if (val.status == "Declined") {
		    		val.status = "<span class='label label-danger signoff-status'>" + val.status + "</span>";
		    	}
					var t = val.requestDate.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					var requestDate = (d.getMonth() + 1)  + "/" + d.getDate() + "/" + d.getFullYear();
         html += "<p class='text-muted" + requestClass + "'><small>" + requestDate + " to " + "<a href='http://signoff.pugetsound.edu/view.php?requestId=" + val.requestId + "' target='_blank'>" + val.reqFullName.replace(/\+/g , " ") + "</a><a href='#' class='get-signoff-link'><span class='glyphicon glyphicon-link'></span> get link</a></small> " + val.status + "<input class='form-control input-sm' type='text' value='http://signoff.pugetsound.edu/respond.php?requestId=" + val.requestId + "' style='display:none;'></p>";
    });
				$('#signoffRequests').html(html);
				if(isNew) {
					$('#signoffRequests input:first').slideToggle("slow", function() {
				    $(this).select();
  			});
				}
			}
		});
	}
	function submitNewRequest() {
		if (validateRequest()) {
			var users = $("input[id='requestUsers[]']").map(function(){return $(this).val();}).get();
			$.getJSON("http://signoff.pugetsound.edu/php/submitNewRequest.php?callback=?", {
				apiToken: "{{$signoff_api_key}}",
				author: "{{$user->username}}",
				typeOfWork: $("#typeOfWork").val(),
				ticketNumber: $("#ticketNumber").val(),
				projectId: $("#projectNumber").val(),
				soundNetLink: $("#soundNetLink").val(),
				lpProjectLink: $("#lpProjectLink").val(),
				sprint: $("#sprintNumber").val(),
				projectName: $("#projectName").val(),
				appDesignerProjects: $("#appDesignerProjects").val(),
				plsqlObjects: $("#plsqlObjects").val(),
				otherObjects: $("#otherObjects").val(),
				projectOwner: $("#projectOwnerSelect").val(),
				summaryWorkCompleted: $("#summaryWorkCompleted").val(),
				testingType: $("#testingTypeSelect").val(),
				requestUsers: users.join()
			}, function(data) {
				if (data.hasOwnProperty("error")) {
					$("#errorValidate").show();
					$("#errorValidate").html("<strong>Active Directory: </strong>" + data.error);
				} else {
					$('#newRequestModal').modal('hide');
					getRequests(true);
				}
			});}
	}
	    $(document).ready(function() {
				getRequests(false);
				clearRequestForm();

				$("#hidedetailfields").hide();
		    $("#testinggroup").hide();
		    $("#sumWorkGroup").hide();

				$('#signoffRequestsContainer h6 small').on('click', function(e){
						$("#signoffRequests .hiddenRequest").slideToggle();
						e.preventDefault();
				});
				$('#signoffRequestsContainer').on('click', '.get-signoff-link', function(e){
						$(this).parent().parent().find('input').slideToggle().select();
						e.preventDefault();
				});

				$('#signoffRequests').on('click', 'input', function(e){
						$(this).select();
				});
		  	//show fields related to the type of work that is being changed.
		  	$('#typeOfWork').on('change', function(){
		  		$("#ticketNumberGroup").hide();
				$("#soundNetGroup").hide();
		    	var selected = $(this).find("option:selected").val();
		    	if (selected == "project" || selected == "req") {
		    		$("#ticketNumber").val("");
		    		$( "#soundNetGroup" ).slideDown(500);
		    		$("#hidedetailfields").show();
		    		$("#testinggroup").show();
		    		$("#sumWorkGroup").show();
		    	}
		    	if (selected == "req") {
		    		$("#hidedetailfields").hide();
		    		$("#testinggroup").hide();
		    		$("#sumWorkGroup").hide();
		    	}
		    	if (selected == "ticket") {
		    		$("#projectNumber").val("");
					$("#soundNetLink").val("");
					$("#lpProjectLink").val("");
		    		$( "#ticketNumberGroup" ).slideDown(500);
		    		$("#hidedetailfields").show();
		    		$("#testinggroup").show();
		    		$("#sumWorkGroup").show();
		    	}
				});
				//hides search & filter, shows requests, initialized new sign-off reqeust form
				$('#newRequestModal').on('show.bs.modal', function (event) {
					//initialize project owners list
					clearRequestForm();
					$("#ticketNumberGroup").hide();
					$("#soundNetGroup").show();
					//open modal
				})
				//add/remove fields to the new request form
			    var max_fields      = 10; //maximum input boxes allowed
			    var wrapper         = $("#input_fields_wrap"); //Fields wrapper
			    var add_button      = $("#addUsers"); //Add button ID

			    var x = 1; //initlal text box count
			    $(add_button).click(function(e){ //on add input button click
			        e.preventDefault();
			        if(x < max_fields){ //max input box allowed
			            x++; //text box increment
			            $(wrapper).append("<div style='width: 325px; margin-top: 2px;' class='input-group request-representative-div'><span class='input-group-addon'><a href='#'' id='remove_field'>Remove</a></span><input type='text' class='form-control request-representative' id='requestUsers[]' placeholder='username' aria-describedby='email-addon'><span class='input-group-addon' id='email-addon'>@pugetsound.edu</span></div>"); //add input box
									$('.request-representative:last').focus();
			        }
			    });

			    $(wrapper).on("click","#remove_field", function(e){ //user click on remove text
			    	if (x != 1) {
			        	e.preventDefault();
			    		$(this).closest('div').remove();
			    		x--;
			    	}
			    })
	    });
	</script>
@endsection
