<div class="modal fade" id='updateStatus'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-body-head"></div>
        {!! Form::open(['method' => 'PATCH', 'action' => ['ProjectsController@update_status']]) !!}
        {!! Form::label('status', 'Change Status:') !!}
        {!! Form::hidden('project_id', null, ['id' => 'project_id_hidden']) !!}
        {!! Form::select('status', ['7' => 'New', '0' => 'Needs Review', '1' => 'Pending', '2' => 'Ready to Schedule', '3' => 'Scheduled/In Progress', '4' => 'Refer to Oracle'], null, ['class' => 'form-control', 'id' => 'updateStatusSelect']) !!}
        {!! Form::textarea('comment_text', null, ['class' => 'form-control', 'placeholder' => 'Optional Comment', 'style' => 'height: 100px; margin-top: 10px;']) !!}
        
        <p class='text-muted'>Looking to mark this project as complete or deferred? Click 'Cancel' and from the dropdown menu select 'Mark as X'</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
        <!--<a id='user-update' href="#" type="button" class="btn btn-primary">Update</a>-->
        {!! Form::close() !!}
      </div>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->


  <div class="modal fade" id='markComplete'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-body-head"></div>
        {!! Form::open(['method' => 'PATCH', 'action' => ['ProjectsController@mark_complete']]) !!}
        {!! Form::hidden('project_id', null, ['id' => 'project_id_complete']) !!}
        {!! Form::textarea('comment_text', null, ['class' => 'form-control', 'placeholder' => 'Optional Comment', 'style' => 'height: 100px; margin-top: 10px;']) !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        {!! Form::submit('Mark Complete', ['class' => 'btn btn-success']) !!}
        <!--<a id='user-update' href="#" type="button" class="btn btn-primary">Update</a>-->
        {!! Form::close() !!}
      </div>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

  <div class="modal fade" id='sprintAssign'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-body-head"></div>
        {!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@assign_project']]) !!}
        {!! Form::hidden('project_id', null, ['id' => 'project_id_assign']) !!}
        <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">Sprint</span>
        {!! Form::select('sprint', $sprints, null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        {!! Form::submit('Assign Project', ['class' => 'btn btn-primary']) !!}
        <!--<a id='user-update' href="#" type="button" class="btn btn-primary">Update</a>-->
        {!! Form::close() !!}
      </div>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

<div class="modal fade" id='sprintDeassign'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-body-head"></div>
        {!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@deassign_project']]) !!}
        {!! Form::hidden('project_id', null, ['id' => 'project_id_deassign']) !!}
        Are you sure you want to deassign this project from a Sprint?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        {!! Form::submit('Deassign Project', ['class' => 'btn btn-danger']) !!}
        <!--<a id='user-update' href="#" type="button" class="btn btn-primary">Update</a>-->
        {!! Form::close() !!}
      </div>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

