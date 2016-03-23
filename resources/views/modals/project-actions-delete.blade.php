  <div class="modal fade" id='deleteProject'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete {{ $project->request_name }}</h4>
      </div>
      <div class="modal-body">
        <div class="modal-body-head"></div>
        Are you sure you want to delete the <strong> {{ $project->request_name }} </strong> project from the Request Manager? <br><br>
        <small class='text-muted'>This action is permanent.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

        <a id='user-update' href="{{ url('request/' . $project->id . '/delete')}}" type="button" class="btn btn-danger">Delete Project</a>

      </div>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->