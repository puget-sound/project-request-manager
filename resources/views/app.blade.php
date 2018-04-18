<!DOCTYPE html>
<html>
	<head>
		<title>@yield('head-title', 'Project Request Manager')</title>

		<!-- Latest compiled and minified CSS -->

    <link rel="stylesheet" href="{{ URL::asset('css/sortable-theme-bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/bootstrap-table.css">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <meta name="_token" content="{{ csrf_token() }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-multiselect.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('css/bootstrap-select.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
		<!-- Latest compiled and minified JavaScript -->
		<style>
    @media (min-width: 1200px) {
      .container {
        width: 1200px;
      }
    }
    </style>
		<link rel="icon" href="{{ URL::asset('images/grey-favicon.png') }}" type="image/png">
	</head>

	<body style="padding-top: 80px;">
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href=" {{ url('/') }} "><img src="{{ URL::asset('images/prm.png') }}" height="75" style="margin-top: -12px;" /></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria=expanded="false"><span class="glyphicon glyphicon-globe"></span>&nbsp;&nbsp;Projects<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ url('requests') }}"><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;My Projects</a></li>
                    <li><a href="{{ url('requests/all') }}"><span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;All Projects</a></li>
                    <li><a href="{{ url('projects/search') }}"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Project Search</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="dropdown-header">Projects by Owner</li>
                  @foreach ($menu_owners as $owner)
                    <li><a href="{{ url('projects/' . $owner->id )}}"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp; {{ $owner->name }} </a>
                  @endforeach
                </ul>
            </li>
						<li><a href="{{ url('view-sprints') }}"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Sprints</a></li>
            @if (Helpers::full_authenticate()->isAdmin())
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Administration<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('sprints') }}"><span class="glyphicon glyphicon-flash"></span>&nbsp;&nbsp;Sprint Management</a></li>
                <li><a href="{{ url('owners') }}"><span class="glyphicon glyphicon-folder-close"></span>&nbsp;&nbsp;Project Owners</a></li>
                <li><a href="{{ url('users') }}"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;System Users</a></li>
              </ul>
            </li>
            @endif
            <li><a href="https://soundnet.pugetsound.edu/sites/Team/WorkTeams/tspmo/SiteAssets/SitePages/PeopleSoft%20and%20Project%20Support%20Resources/Project%20Request%20Manager%20Instructions.pdf" target="_blank"><span class="glyphicon glyphicon-earphone"></span>&nbsp;&nbsp;Support</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          	<li><a href="{{ url('notifications') }}"><span class="glyphicon glyphicon-flag"></span>&nbsp;&nbsp;<span class='badge' id='notifcount'></span></a></li>
            <li>
              <a class='dropdown-toggle' data-toggle='dropdown' role='button' href="#"><span class='glyphicon glyphicon-lock'></span>&nbsp;&nbsp;{{ Helpers::full_authenticate()->fullname }}<span class='caret'></span></a>
                <ul class='dropdown-menu'>
                  <li><a href='{{ url('logout') }}'>Sign out</a></li>
                </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
    	<div class='page-header'>
				<div class="title-right">
					@yield('title-right')
				</div>
    		<h2>@yield('title')</h2>
				@yield('under-title')
    	</div>
		@yield('content')
	</div>
	<!-- Placed at the end of the document so the pages load faster -->
	<script>
	var base_url = "{{$base_url}}";
	</script>
  <script type="text/javascript" src="{{ URL::asset('js/wysihtml5x-toolbar.min.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/bootstrap-table-all.min.js"></script>-->
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ URL::asset('js/sortable.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/handlebars.runtime.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/bootstrap3-wysihtml5.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('functions.js') }}"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
    @yield('extra-scripts')
	</body>
</html>
