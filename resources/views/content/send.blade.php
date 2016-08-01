@extends('app')
@include('errors.list')
@section('title')
Send to LiquidPlanner
@endsection

@section('content')

<div class="row">
  <div class="col-md-5" id="project-send">
{!! Form::model($project, ['method' => 'GET', 'action' => ['ProjectsController@process_send', $project->id]]) !!}
{!! Form::hidden('project_id', $project->id) !!}
<div class="form-group">
{!! Form::label('request_name', 'Project Name') !!}
{!! Form::text('request_name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
{!! Form::label('lp_owner', 'LiquidPlanner Owner') !!}
{!! Form::select('lp_owner', $lp_owners, null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
{!! Form::label('project_owner', 'LiquidPlanner Client') !!}
{!! Form::select('project_owner', $owners, null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
  {!! Form::label('lp_parent', 'LiquidPlanner Parent Folder') !!}
  {!! Form::select('lp_parent', $lp_parent, null, ['class' => 'form-control']) !!}
</div>
{!! Form::submit("Send to LiquidPlanner", ['class' => 'btn btn-primary form-control']) !!}
{!! Form::close() !!}
<a href="{{ url('request') }}/{{ $project->id }}">Cancel</a>
</div>
</div>
@endsection

@section('extra-scripts')
  <script>
    $(document).ready(function() {
      $('#projectNumber2Label').on('click', function() {
        $('#project_number').removeClass('hidden').addClass('show');
      });
      $('#projectNumber1Label').on('click', function() {
        $('#project_number').addClass('hidden').removeClass('show');
      });
    });
  </script>
@endsection
