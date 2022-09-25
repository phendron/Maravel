<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="{{ URL::asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/manage-app-routes.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<title>Manage Project</title>
</head>

<body>
	
@include('menu.default')
	
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $project['name'] }}</li>
  </ol>
</nav>
	
<h1>{{ $project['name'] }}</h1>
	
	
	<h2>Cached Routes</h2>
	<section class="routes">
	<table class="table routes-table">
	<thead>
    <tr>
	<th>Route</th>
	<th>Methods</th>
	<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	@foreach ($project['routes'] as $route)
		<tr>
		<td>{{ $route['uri'] }}</td>
		<td>
		@foreach ($route['methods'] as $method)
			<span class="badge text-bg-dark"><b>{{ $method }}</b></span>
		@endforeach
		</td>
		<td><a href="../../{{ $route['uri'] }}"><button class="btn btn-primary"><i style="color: #fff !important; font-size: 15px; margin-right: 10px;"class="bi bi-link text-primary"></i>view</button></a></td>
		</tr>
	@endforeach
			
		</tbody>
	</table>
	</section>

</body>
</html>