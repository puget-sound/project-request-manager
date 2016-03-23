@extends('app')

@section('title')
Notifications
@endsection

@section('content')
@include('errors.list')



<style>
.bs-callout {
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
}
.bs-callout h4 {
    margin-top: 0;
    margin-bottom: 5px;
}
.bs-callout p:last-child {
    margin-bottom: 0;
}
.bs-callout code {
    border-radius: 3px;
}
.bs-callout+.bs-callout {
    margin-top: -5px;
}
.bs-callout-default {
    border-left-color: #777;
}
.bs-callout-default h4 {
    color: #777;
}
.bs-callout-primary {
    border-left-color: #428bca;
}
.bs-callout-primary h4 {
    color: #428bca;
}
.bs-callout-success {
    border-left-color: #5cb85c;
}
.bs-callout-success h4 {
    color: #5cb85c;
}
.bs-callout-danger {
    border-left-color: #d9534f;
}
.bs-callout-danger h4 {
    color: #d9534f;
}
.bs-callout-warning {
    border-left-color: #f0ad4e;
}
.bs-callout-warning h4 {
    color: #f0ad4e;
}
.bs-callout-info {
    border-left-color: #5bc0de;
}
.bs-callout-info h4 {
    color: #5bc0de;
}
</style>

@foreach ($notifications as $notification)
@if ($notification->updated_at > $last_check->updated_at)
<div class="bs-callout bs-callout-primary">
@else
<div class="bs-callout bs-callout-default">
@endif
    <h4><a style='color: inherit;' href="{{ url('request') . "/" . $notification->request_id }}">{{ $notification->request_name }}</a></h4>
    <p>@if ($notification->flag == 'P') Project description or details were updated. @else {{ $notification->fullname }} left a comment on this project. @endif</p>
    <small class='text-muted'><span class='glyphicon glyphicon-time'></span>&nbsp;&nbsp;{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->updated_at)->diffForHumans() }}</small>
</div>
@endforeach


{{ Helpers::updateLastCheck() }}
@endsection