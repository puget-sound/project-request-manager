@if (Session::has('success'))
		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p class="text-center">{!! Session::get('success') !!}</p>
		</div>
@endif
@if ($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				<p class="text-center"> {{ str_replace('request name', 'Project Name', $error) }} </p>
			@endforeach
		</div>
@endif
