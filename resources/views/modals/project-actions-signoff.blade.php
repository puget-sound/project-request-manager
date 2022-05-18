<div class='modal fade' role='dialog' id='newRequestModal'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">New Sign-off Request</h4>
      </div>
      <div class="modal-body">
      <form class='form-horizontal'>
        <div class="form-group">
          <label for="projectOwnerSelect" class="col-sm-4 control-label">Type</label>
          <div class="col-sm-8">
            <select id='typeOfWork' class="selectpicker" title="Please Select One...">
              <option value='1' data-hidden="true"></option>
              <option value='project'>Project</option>
              <option value='ticket'>KACE Ticket</option>
              <option value='req'>Requirements &amp; Docs</option>
            </select>
          </div>
        </div>
        <div class="form-group" id='ticketNumberGroup'>
          <label for="projectNumber" class="col-sm-4 control-label">Ticket Number</label>
          <div class="col-sm-8">
            <div class='input-group'>
            <span class="input-group-addon">
                TICK:
              </span>
            <input style="width: 100px;"type="text" class="form-control" id="ticketNumber" placeholder="34833">
            </div>
          </div>
        </div>
        <div id='soundNetGroup'>
            <div class="form-group">
              <label for="projectNumber" class="col-sm-4 control-label">Project ID</label>
              <div class="col-sm-8">
                <input style="width: 100px;"type="text" class="form-control" id="projectNumber" placeholder="P0100">
              </div>
            </div>
            <div class="form-group">
              <label for="soundNetLink" class="col-sm-4 control-label">Google Drive Folder Link</label>
              <div class="col-sm-8">
                <input style="width: 300px;"type="text" class="form-control" id="soundNetLink" placeholder="Paste Google Drive Link here">
              </div>
            </div>
            <div class="form-group">
              <label for="lpProjectLink" class="col-sm-4 control-label">LP Task Link</label>
              <div class="col-sm-8">
                <input style="width: 300px;"type="text" class="form-control" id="lpProjectLink" placeholder="Paste LiquidPlanner Link here">
              </div>
            </div>
      </div>
        <div class="form-group">
          <label for="sprintNumber" class="col-sm-4 control-label">Sprint</label>
          <div class="col-sm-8">
            <input style="width: 75px;"type="text" class="form-control" id="sprintNumber" placeholder="10">
          </div>
        </div>
       <div class="form-group">
          <label for="projectName" class="col-sm-4 control-label">Project Name *</label>
          <div class="col-sm-8">
            <input style="width: 250px;"type="text" class="form-control" id="projectName" placeholder="SoundNet Project or Ticket Name">
          </div>
        </div>
        <div id='hidedetailfields'>
        <div class="form-group">
          <label for="appDesignerProjects" class="col-sm-4 control-label">App Designer Projects</label>
          <div class="col-sm-8">
            <input style="width: 250px;"type="text" class="form-control" id="appDesignerProjects" placeholder="If applicable, separate by commas.">
          </div>
        </div>
        <div class="form-group">
          <label for="appDesignerProjects" class="col-sm-4 control-label">PL/SQL Objects</label>
          <div class="col-sm-8">
            <input style="width: 250px;"type="text" class="form-control" id="plsqlObjects" placeholder="If applicable, separate by commas.">
          </div>
        </div>
        <div class="form-group">
          <label for="appDesignerProjects" class="col-sm-4 control-label">Other</label>
          <div class="col-sm-8">
            <input style="width: 250px;"type="text" class="form-control" id="otherObjects" placeholder="If applicable, separate by commas.">
          </div>
        </div>
      </div>
        <div class="form-group">
          <label for="projectOwnerSelect" class="col-sm-4 control-label">Project Owner *</label>
          <div class="col-sm-8">
            <select id='projectOwnerSelect' class="selectpicker" title="Please Select One..." data-live-search="true">
              <option data-hidden="true" value='1'></option>
              @if (empty($signoff_owners))
	            	<option value="Technology Services">Technology Services</option>
				      @else
              @foreach ($signoff_owners as $owner)
                <option value="{{ $owner->ownerName }}">{{ $owner->ownerName }}</option>
              @endforeach
            @endif
            </select>
          </div>
        </div>
        <div class="form-group" id='sumWorkGroup'>
          <label for="summaryWorkCompleted" class="col-sm-4 control-label">Summary of Work Completed *</label>
          <div class="col-sm-8">
            <textarea style="width: 300px;" id="summaryWorkCompleted" class="form-control" rows=10 placeholder="Summarize Work Completed for this Project. Including and phasing / caveats"></textarea>
          </div>
        </div>
        <div class="form-group" id='testinggroup'>
          <label for="testingTypeSelect" class="col-sm-4 control-label">Proof of Testing *</label>
          <div class="col-sm-8">
          <select id='testingTypeSelect' class="selectpicker" title="Testing provided by:">
              <option data-hidden="true" value='1'></option>
              <option value='link'>Excel Spreadsheet / Link</option>
              <option value='text'>Testing Summary (Text)</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="addAdditionalUser" class="col-sm-4 control-label">Request Sign-off From *</label>
          <div class="col-sm-8" id='input_fields_wrap'>
            <div style='width: 325px; margin-top: 2px;' class="input-group request-representative-div">
              <span class="input-group-addon">
                <a href="#" id="remove_field">Remove</a>
              </span>
              <input type="text" class="form-control request-representative" id="requestUsers[]" placeholder="username" aria-describedby="email-addon">
              <span class="input-group-addon" id="email-addon">@pugetsound.edu</span>
            </div>
          </div>
          <div class="col-sm-4 col-sm-offset-4" style="margin-top:5px;">
            <button type="button" class="btn btn-default btn-sm" id="addUsers">Add additional user</button>
          </div>
        </div>
      </form>
      <div style="display: none;" class="alert alert-danger" id="errorValidate" role="alert"></div>
<!-- end modal -->
</div>
<div class="modal-footer">
   <button type="button" onclick="refreshView();" class="btn btn-link" data-dismiss="modal">Cancel</button><button style='margin-left: 2px' type="button" class="btn btn-default" onclick="submitNewRequest();">Submit</button>
</div>
</div>
</div>
</div>
