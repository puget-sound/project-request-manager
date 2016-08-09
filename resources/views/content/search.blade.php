@extends('app')
@include('errors.list')
@section('title')
Project Search
@endsection

@section('content')

<div class="row">
  <div class="col-md-5" id="project-search">
{!! Form::open(['method' => 'GET', 'action' => ['ProjectsController@process_search']]) !!}
	<div class="form-group">
	{!! Form::label('sq_n', 'Project Name ') !!}
	{!! Form::text('sq_n', null, ['class' => 'form-control']) !!}
	</div>

	<div class="form-group">
	{!! Form::label('sq_o', 'Owner ') !!}
	<br>
	<select multiple="multiple" id="sq_o" name="sq_o[]" class="form-control" data-label="Any owner">
		<!--<option value="">Any</option>-->
		@foreach ($owners as $owner)
		<option value="{{ $owner->id }}">{{ $owner->name }}</option>
		@endforeach
	</select>
	</div>

	<div class="form-group">
	{!! Form::label('sq_s', 'Status ') !!}
	<div class='form-inline'>
		{!! Form::select('sq_s[]', ['7' => 'New', '0' => 'Needs Review', '1' => 'Pending', '2' => 'Ready to Schedule', '3' => 'Scheduled', '4' => 'Refer to Oracle', '5' => 'Deferred'], null, ['class' => 'form-control', 'multiple' => 'multiple', 'id' => 'sq_s', 'data-label' => 'Any status']) !!}
	</div>
  <div class="checkbox">
    <label>
      {!! Form::checkbox('sq_co', 'Y', null, null) !!} Include Completed projects
    </label>
  </div>
	</div>
	<div class="form-group">
	{!! Form::label('sq_p', 'Project Priority ') !!}
  <br>
	{!! Form::select('sq_p[]', ['0' => 'High', '1' => 'Medium', '2' => 'Low'], null, ['class' => 'form-control', 'multiple'=> 'multiple', 'id'=>'sq_p', 'data-label'=> 'Any priority']) !!}
	</div>
	<div class="form-group">
	{!! Form::label('sq_ip', 'ERP Cateogry ') !!}
  <br>
	{!! Form::select('sq_ip[]', ['0'=> 'Undetermined', '1' => 'Category 1', '2' => 'Category 2', '3' => 'Category 3', '4' => 'Category 4'], null, ['class' => 'form-control', 'multiple' => 'multiple', 'id' => 'sq_ip', 'data-label' => 'Any category']) !!}
	</div>
	<div class="checkbox">
		<label>
			{!! Form::checkbox('sq_c', 'C', null, null) !!} Project in Cascade
		</label>
	</div>
	{!! Form::submit("Search", ['class' => 'btn btn-primary form-control']) !!}
{!! Form::close() !!}
</div>
</div>



@endsection

@section('extra-scripts')
  <script type="text/javascript" src="{{ URL::asset('js/bootstrap-multiselect.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        $('#sq_o, #sq_s, #sq_ip, #sq_p').multiselect({
            buttonText: function(options, select) {
            	if (options.length === 0) {
                    return (select).data('label');
                }
                else {
                     var labels = [];
                     options.each(function() {
                         if ($(this).attr('label') !== undefined) {
                             labels.push($(this).attr('label'));
                         }
                         else {
                             labels.push($(this).html());
                         }
                     });
                     return labels.join(', ') + '';
                 }
            },
            maxHeight: 205,
            buttonClass: 'btn btn-default sq_select_button',
        });
    });
  </script>
@endsection
