@extends('app')

@section('title')
Send to LiquidPlanner
@endsection

@section('content')
@include('errors.list')

<div class="row">
  <div class="col-md-5" id="project-send">
{!! Form::model($project, ['method' => 'GET', 'action' => ['ProjectsController@process_send', $project->id]]) !!}
{!! Form::hidden('project_id', $project->id) !!}
<div class="form-group">
{!! Form::label('request_name', 'Name') !!}
{!! Form::text('request_name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
{!! Form::label('project_owner', 'Client') !!}
{!! Form::select('project_owner', $owners, null, ['class' => 'form-control']) !!}
</div>

{!! Form::submit("Send to LiquidPlanner", ['class' => 'btn btn-primary form-control']) !!}
{!! Form::close() !!}
</div>
</div>
@endsection
