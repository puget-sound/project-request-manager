@extends('app')
@include('errors.list')
@section('title')
System Settings
@endsection
@section('content')
<div class="tabbable">
	{{Helpers::sync_names()}}
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a href="#tab1" data-toggle="tab">
					ERP Categories
				</a>
			</li>
			<li>
				<a href="#tab2" data-toggle="tab">
					Sprint Phases
				</a>
			</li>
			<li>
				<a href="#tab3" data-toggle="tab">
					Sprint Statuses
				</a>
			</li>
			<li>
				<a href="#tab4" data-toggle="tab">
					Roles
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-10">
		<div class="tab-content">
			<div class="tab-pane" id="tab1">

					<div class="row">
						<div class="panel panel-default">
							<div class="panel-heading">
								ERP Categories
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<ul class="list-group borderless">
										@foreach($categories as $category)
											<li class="list-group-item">
												<div>
													{{$category->name}}
												</div>

											</li>
										@endforeach
									</ul>
								</div>
								<div class="col-md-4 form-group">
									{!! Form::open(['url' => 'adderpcategory']) !!}
									{!! Form::text('name', null, ['class' => 'form-control']) !!}
								</div>
								<div class="col-md-2 form-group">
									{!! Form::submit("Add ERP Category", ['class' => 'btn btn-primary']) !!}
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>

			</div>
			<div class="tab-pane" id="tab2">

					<div class="row">
						<div class="panel panel-default">
							<div class="panel-heading">
								Sprint Phases
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<ul class="list-group borderless">
										@foreach($phases as $phase)
											<li class="list-group-item">
												<div>
													{{$phase->name}}
												</div>

											</li>
										@endforeach
									</ul>
								</div>
								<div class="col-md-4 form-group">
									{!! Form::open(['url' => 'addsprintphase']) !!}
									{!! Form::text('name', null, ['class' => 'form-control']) !!}
								</div>
								<div class="col-md-2 form-group">
									{!! Form::submit("Add Sprint Phase", ['class' => 'btn btn-primary']) !!}
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>

			</div>
			<div class="tab-pane" id="tab3">

					<div class="row">
						<div class="panel panel-default">
							<div class="panel-heading">
								Sprint Phases
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<ul class="list-group borderless">
										@foreach($statuses as $status)
											<li class="list-group-item">
												<div>
													{{$status->name}}
												</div>

											</li>

										@endforeach
									</ul>
								</div>
								<div class="col-md-4 form-group">
									{!! Form::open(['url' => 'addsprintstatus']) !!}
									{!! Form::text('name', null, ['class' => 'form-control']) !!}
								</div>
								<div class="col-md-2 form-group">
									{!! Form::submit("Add Sprint Status", ['class' => 'btn btn-primary']) !!}
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>

			</div>
			<div class="tab-pane" id="tab4">

					<div class="row">
						<div class="panel panel-default">
							<div class="panel-heading">
								Sprint Project Roles
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<ul class="list-group borderless">
										@foreach($roles as $role)
											<li class="list-group-item">
												<div>
													{{$role->name}}
												</div>

											</li>
										@endforeach
									</ul>
								</div>
								<div class="col-md-4 form-group">
									{!! Form::open(['url' => 'addsprintprojectrole']) !!}
									{!! Form::text('name', null, ['class' => 'form-control']) !!}
								</div>
								<div class="col-md-2 form-group">
									{!! Form::submit("Add Sprint Role", ['class' => 'btn btn-primary']) !!}
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>

			</div>
		</div>
	</div>
</div>
@endsection
@section('extra-scripts')
<script>

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
  }

</script>
@endsection
