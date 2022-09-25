<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/manage-app-logs.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/manage-app-logs.js') }}"></script>
<title>Untitled Document</title>
</head>

<body>
	
<section class="logs">

@include('menu.default')
	
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Environment</li>
  </ol>
</nav>
	
	
	
<h1>{{ $project['name'] }} | Environment</h1>
	
	
<section>
<filter>
<select name="datetime">
<option selected disabled>Order by Date / Time</option>
<option value="">Newest</option>
<option value="">Oldest</option>
</select>
	
<select name="error">
<option selected value="NONE">Filter by Level</option>
<option value="DEBUG">Debug</option>
<option value="INFO">Info</option>
<option value="ERROR">Error</option>
<option value="ALERT">Alert</option>
<option value="WARNING">Warning</option>
<option value="NOTICE">Notice</option>
<option value="CRITICAL">Critical</option>
<option value="EMERGENCY">Emergency</option>
</select>
	
<button class="btn btn-primary">Real Time Monitor</button>
</filter>	
</section>
<div class="table-responsive">
<table  class="table table-striped table-dark table-hover">
<thead class="thead-dark">
<tr>
<th style="width: 33.33%" scope="col">Date</th>	
<th style="width: 33.33%" scope="col">Level</th>
<th style="width: 33.33%" scope="col">Message</th>
</tr>
</thead>	
<tbody>
@foreach($logs as $log)
	<tr class="@switch($log['level']) @case('DEBUG') table-primary @break @case('INFO') table-info @break @case('ERROR') table-primary @break @case('ALERT') table-danger @break @case('WARNING') table-warning @break @case('NOTICE') table-warning @break @case('CRITICAL') table-danger @break @case('EMERGENCY') table-danger @break @endswitch">
	<td scope="row">{{ $log['date'] }}</td>
	<td class="error-level" scope="row" value="{{ $log['level'] }}">{{ $log['level'] }}</td>
	<td scope="row">{{ $log['message'] }} {{ $log['context'] }} {{ $log['extra'] }}</td>
	</tr>
@endforeach
	
</tbody>
</table>
</div>
	
</section>

</body>
</html>