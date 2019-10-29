@extends('app')
@include('errors.list')
@include('settings.google')
@section('title')
Project Folders
@endsection

@section('content')

<div class="row">
  <div class="col-md-5">
<div id="google-project-folders"></div>
<div id="page-nav"></div>

</div>
</div>



@endsection

@section('extra-scripts')
  <script type="text/javascript" src="{{ URL::asset('js/jquery.simplePagination.js') }}"></script>
	<script type="text/javascript">
	google_search_type = "all",
  apiKey = "{{$GAapiKey}}",
  clientId = "{{$GAclientId}}",
  google_parents = [],
  google_parents_query = "",
  google_content = "Project Folders";

  @foreach ($owners as $owner)
    google_parents.push("{{ $owner->google_id }}");
    @if($owners->last() === $owner)
         google_parents_query += "'{{ $owner->google_id }}' in parents";
    @else
        google_parents_query += "'{{ $owner->google_id }}' in parents or ";
     @endif

  @endforeach

	</script>

	<script type="text/javascript" src="{{ URL::asset('js/google-drive.js') }}"></script>
<script async defer src="https://apis.google.com/js/api.js"
				onload="this.onload=function(){};handleClientLoad()"
				onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
@endsection
