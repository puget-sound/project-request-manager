@extends('app')
@include('errors.list')
@section('title')
Create New Project Request
@endsection

@section('content')

{!! Form::open(['url' => 'requests']) !!}

	@include('content._projectform', ['submitText' => 'Add Project Request', 'disable' => false])

{!! Form::close() !!}


@endsection
@section('extra-scripts')
	<script type="text/javascript">
		//var owner_id = "{{$first_owner}}";
		var owner_id = $("#project_owner").val();
		//var priority = 0;
		var priority = $("#priority").val();
	    $(document).ready(function() {
				getNextOrder();
				//Grab the AJAX Data we need via AjaxController
				function getNextOrder () {
					$.ajax({
						context: $(this),
						url: base_url + '/owners/' + owner_id + '/manage/nextOrder/' + priority,
						type: 'GET',
						success: function(data) {
							$("#priorityOrder").val(data.priorityOrder);
						},
						error: function(data) {
							alert('error');
						}
					});
				}
	    	$( "#project_owner" ).on( "change", function() {
	  			owner_id = $(this).val();
					priority = $("#priority").val();
					getNextOrder();
				});
				$( "#priority" ).on( "change", function() {
	  			owner_id = $("#project_owner").val();
					priority = $(this).val();
					getNextOrder();
				});
	    });
	</script>
@endsection
