<div class='modal fade' role='dialog' id='googleDriveModal'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Create Google Drive Folder</h4>
      </div>
      <div class="modal-body">
      <div style="display: none;" class="alert alert-danger" id="errorValidate" role="alert"></div>
      <form class='form-horizontal'>
            <div class="form-group">
              <label for="folderName" class="col-sm-4 control-label">Folder Name *</label>
              <div class="col-sm-8">
                <input style="width: 300px;"type="text" class="form-control" id="folderName" value="{{$project->project_number}} - {{ $project->request_name }}">
              </div>
            </div>
        <div class="form-group">
          <label for="projectOwnerSelect" class="col-sm-4 control-label">Project Owner *</label>
          <div class="col-sm-8">
            <select id='googleOwnerSelect' class="selectpicker" title="Please Select One..." data-live-search="true">
              <option data-hidden="true" value='1'></option>
              @foreach($owners as $owner)
                @if ($project->name == $owner->name)
                <option value="{{ $owner->google_id }}" selected>{{ $owner->name }}</option>
              @else
                <option value="{{ $owner->google_id }}">{{ $owner->name }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </form>
<!-- end modal -->
</div>
<div class="modal-footer">
   <button type="button" onclick="refreshView();" class="btn btn-link" data-dismiss="modal">Cancel</button><button style='margin-left: 2px' type="button" class="btn btn-default" onclick="createDriveFolder();">Create</button>
</div>
</div>
</div>
</div>
