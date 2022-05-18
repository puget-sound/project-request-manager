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
  $("#projectNumber").val(project_number);
  $("#soundNetLink").val("");
  if(google_folder_id != "") {
    $("#soundNetLink").val("https://drive.google.com/drive/folders/" + google_folder_id);
  }
  $("#lpProjectLink").val("");
  if(lp_id != "") {
    $("#lpProjectLink").val("https://app.liquidplanner.com/space/" + lp_workspace + "/projects/show/" + lp_id);
  }
  $("#sprintNumber").val(sprints);
  $("#projectName").val(project_name);
  $("#appDesignerProjects").val("");
  $("#plsqlObjects").val("");
  $("#otherObjects").val("");
  $(".request-representative").val("");
  $("#projectOwnerSelect").val(signoff_owner);
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
  } else if ($("#testingTypeSelect").val() == 1 && $("#typeOfWork").val() != "req") {
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
  $.getJSON(signoff_base_url + "php/getRequestsByProjectId.php?callback=?", {
    projectId: project_number,
    apiToken: signoff_api_key,
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
       html += "<p class='text-muted" + requestClass + "'><small>" + requestDate + " to " + "<a href='" + signoff_base_url + "view.php?requestId=" + val.requestId + "' target='_blank'>" + val.reqFullName.replace(/\+/g , " ") + "</a><a href='#' class='get-signoff-link'><span class='glyphicon glyphicon-link'></span> get link</a></small> " + val.status + "<input class='form-control input-sm' type='text' value='" + signoff_base_url + "respond.php?requestId=" + val.requestId + "' style='display:none;'></p>";
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
    $.getJSON(signoff_base_url + "php/submitNewRequest.php?callback=?", {
      apiToken: signoff_api_key,
      author: author,
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
    });
  }
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
