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
