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
