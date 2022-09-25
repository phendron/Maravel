<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="{{ URL::asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<title>App List</title>
</head>

<body>
	
@include('menu.default')
	
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Home</li>
  </ol>
</nav>
	

	<h2>Projects</h2>
	<input name="search" value="" placeholder="Search Projects By Name" />
	<table class="table projects-table">
	<thead>
	<tr>
	<th>Name</th>
	<th>Enabled</th>
	<th>Route Cache</th>
	<th>Actions</th>
	</tr>
		</thead>
	<tbody>
	@foreach ($projects as $project)
		<tr>
		<td class="name">{{ $project['name'] }}</td>
			
			<td>
			@if($project['enabled']==false)
			<button type="button" class="btn btn-danger" disabled>Disabled</button>
			{{ $project['enabled'] }}
			@else
			<button type="button" class="btn btn-success" disabled>Enabled</button>
			@endif
			</td>
			
			<td>
			
			@if($project['cacheEnabled']==1)  
		    <button type="button" class="btn btn-success" disabled>Enabled</button>
			@else 
		    <button type="button" class="btn btn-danger" disabled>Disabled</button>
			@endif
		    </td>
			
			<td>
			<div class="btn-group" role="group" aria-label="Basic mixed styles example">
			<a type="button" class="btn btn-outline-primary project-manage" href="{{ url('manage/' . $project['name'] )}}">Manage</a>
			<!-- <button type="button" class="btn btn-outline-primary project-routes">Routes</button> -->
			</div>	
			</td>

		</tr>
	@endforeach
	</tbody>
	</table>
	
	
</body>
</html>