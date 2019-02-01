<div class="modal fade" id='sprintExtend'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Extend to Next Sprint</h4>
      </div>
      <div class="modal-body">
        <div class="modal-body-head"></div>
        {!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@extend_project']]) !!}
        {!! Form::hidden('project_id', null, ['id' => 'project_id_extend']) !!}
        {!! Form::hidden('sprint_id', $sprint->id, ['id' => 'sprint_id_extend']) !!}
        Extend project into next sprint?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        {!! Form::submit('Extend Project', ['class' => 'btn btn-success']) !!}
        <!--<a id='user-update' href="#" type="button" class="btn btn-primary">Update</a>-->
        {!! Form::close() !!}
      </div>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

  <div class="modal fade" id='sprintMove'>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Extend to Next Sprint</h4>
        </div>
        <div class="modal-body">
          <div class="modal-body-head"></div>
          {!! Form::open(['method' => 'PATCH', 'action' => ['SprintsController@move_project']]) !!}
          {!! Form::hidden('project_id', null, ['id' => 'project_id_move']) !!}
          {!! Form::hidden('sprint_id', $sprint->id, ['id' => 'sprint_id_move']) !!}
          Reschedule project for next sprint?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          {!! Form::submit('Move Project', ['class' => 'btn btn-success']) !!}
          <!--<a id='user-update' href="#" type="button" class="btn btn-primary">Update</a>-->
          {!! Form::close() !!}
        </div>
      </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
