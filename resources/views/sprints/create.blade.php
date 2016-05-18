@extends('app')

@section('title')
Add Sprint
@endsection

@section('content')
	<div class="row">
	  <div class="col-md-6">
{!! Form::open(['url' => 'sprints', 'id'=> 'sprintCreate']) !!}

	@include('errors.list')
	<div class="form-group">
		{!! Form::label('sprintNumber', 'Sprint Number ') !!}
		<div class="input-group">
			  <span class="input-group-addon" id="basic-addon1">Sprint</span>
			{!! Form::text('sprintNumber', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('sprintStart', 'Sprint Start ') !!}
		{!! Form::input('date', 'sprintStart', null, ['class' => 'form-control','id' => 'sprintStart']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('sprintEnd', 'Sprint End ') !!}
		{!! Form::input('date', 'sprintEnd', null, ['class' => 'form-control', 'id'=> 'sprintEnd']) !!}
	</div>
		{!! Form::submit('Add Sprint', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
	<a href="{{ url('sprints') }}">Cancel</a>
	</div>
</div>
@endsection

@section('extra-scripts')
<script type="text/javascript" src="{{ URL::asset('js/tableExport.min.js') }}"></script>
<script type="text/javascript">
$(function() {
$( "#sprintStart, #sprintEnd" ).datepicker({
			dateFormat: "yy-mm-dd",
			showOtherMonths: true,
      selectOtherMonths: true,
			onClose: function( selectedDate ) {
    		$( "#sprintEnd" ).datepicker( "option", "minDate", selectedDate );
  		}});
});
</script>
@endsection
