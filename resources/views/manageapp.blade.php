<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="{{ URL::asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/manage-app.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/manage-app.js') }}"></script>
<style></style>
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
	
<h1>{{ $project['name'] }} | Dashboard</h1>
	
	
	<section class="app-info">
	<button class="btn btn-primary">Host:
	<span class="badge bg-primary host">{{ $project['process']['host'] }}</span>
	</button>
	<button class="btn btn-primary">Port:	
	<span class="badge bg-primary port">{{ $project['process']['port'] }}</span>
	</button>
	<a href="http://{{$project['process']['host']}}:{{$project['process']['port']}}" class="@if(!$project['enabled']) disabled @endif app-link">
	<button class="btn btn-primary">Launch App</button>
	</a>
	</section>
	
	<input type="hidden" name="name" value="{{ $project['name'] }}" />
	
	<section class="card-panel">
	<div class="row row-cols-3 row-cols-md-3 g-4">
		<div class="col">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Artisan Server</h5>
      <p class="card-text">
			<div class="d-grid gap-2 col-6 mx-auto">
 		 <button action-type="serve" action="@if($project['enabled']) 0 @else 1 @endif" class="action-btn btn @if($project['enabled']) btn-danger @else btn-success @endif" type="button">
		@if($project['enabled'])
		Stop
		@else
		Start
	    @endif
		</button>
		</div>
		</p>
    </div>
    <div class="card-footer">
      <small class="text-muted">
	<button type="button" class="btn btn-primary">
  Status: <span class="badge bg-secondary" state-text="['Shutdown','Running']">
	@if($project['enabled'])
	Running
	@else
	Shutdown
	@endif
	</span>
</button>
	  </small>
    </div>
  </div>
	</div>
		
	<div class="col">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Maintinance Mode</h5>
      <p class="card-text">
		 		<div class="d-grid gap-2 col-6 mx-auto">
 		 <button action-type="maintenance" action="@if($project['maintenance']) 0 @else 1 @endif" class="action-btn btn @if($project['maintenance']) btn-danger @else btn-success @endif" type="button">
		@if($project['maintenance'])
		Disable
		@else
		Enable
		@endif				
		</button>
		</div>
	  </p>
    </div>
    <div class="card-footer">
      <small class="text-muted">
		<button type="button" class="btn btn-primary">
  Status: <span class="badge bg-secondary" state-text="['Disabled','Enabled']">
		@if($project['maintenance'])
		Enabled
		@else
		Disabled
		@endif	
		</span>
</button>
	  </small>
    </div>
  </div>
	</div>
			
	<div class="col">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Config Cache</h5>
      <p class="card-text">
		 		<div class="d-grid gap-2 col-6 mx-auto">
 		 <button action-type="config" action="@if($project['config']) 0 @else 1 @endif" class="action-btn btn @if($project['config']) btn-danger @else btn-success @endif" type="button">
		@if($project['config'])
		Disable
		@else
		Enable
		@endif				
		</button>
		</div>	
	</p>
    </div>
    <div class="card-footer">
      <small class="text-muted">
		<button type="button" class="btn btn-primary">
  Status: <span class="badge bg-secondary" state-text="['Disabled','Enabled']">
		@if($project['config'])
		Enabled
		@else
		Disabled
		@endif		
		 </span>
</button> 
	  </small>
    </div>
  </div>
	</div>
		
	<div class="col">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Routes Cache</h5>
      <p class="card-text">
				<div class="d-grid gap-2 col-6 mx-auto">
 		 <button action-type="cache" action="@if($project['cacheEnabled']) 0 @else 1 @endif" class="action-btn btn @if($project['cacheEnabled']) btn-danger @else btn-success @endif" type="button" state-text="['Disabled','Enabled']">
				@if($project['cacheEnabled'])
				Disable
				@else
				Enable
				@endif				
		</button>
		</div>  
	</p>
    </div>
    <div class="card-footer">
      <small class="text-muted">
		<button type="button" class="btn btn-primary">
  Status: <span class="badge bg-secondary" state-text="['Disabled','Enabled']">
				@if($project['cacheEnabled'])
				Enabled
				@else
				Disabled
				@endif	
		  </span>
</button>	
	  </small>
			<button style="display:none;" action-type="cache" action="1" class="action-btn btn btn-warning refresh-route-cache" type="button">Update Cache</button>
    </div>
  </div>
	</div>
		
	<div class="col">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Views Cache</h5>
      <p class="card-text">
		  		<div class="d-grid gap-2 col-6 mx-auto">
 		 <button action-type="view" action="@if($project['view']) 0 @else 1 @endif" class="action-btn btn @if($project['view']) btn-danger @else btn-success @endif" type="button" state-text="['Disabled','Enabled']">
				@if($project['view'])
				Disable
				@else
				Enable
				@endif			
		</button>
		</div>
	  </p>
    </div>
    <div class="card-footer">
      <small class="text-muted">
	<button type="button" class="btn btn-primary">
  Status: <span class="badge bg-secondary" state-text="['Disabled','Enabled']">
				@if($project['view'])
				Enabled
				@else
				Disabled
				@endif
		 </span>
</button>
		  <button style="display:none;" action-type="cache" action="1" class="action-btn btn btn-warning refresh-view-cache" type="button">Update Cache</button>
	  </small>
    </div>
  </div>
	</div>
		
	<div class="col">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Events Cache</h5>
      <p class="card-text">
			<div class="d-grid gap-2 col-6 mx-auto">
 		 <button action-type="event" action="@if($project['event']) 0 @else 1 @endif" class="action-btn btn @if($project['event']) btn-danger @else btn-success @endif" type="button" state-text="['Disabled','Enabled']">
				@if($project['event'])
				Disable
				@else
				Enable
				@endif	
		</button>
		</div>
	  </p>
    </div>
    <div class="card-footer">
      <small class="text-muted">
		 	<button type="button" class="btn btn-primary">
  Status: <span class="badge bg-secondary" state-text="['Disabled','Enabled']">
				@if($project['event'])
				Enabled
				@else
				Disabled
				@endif	
		  </span>
</button>
	  </small>
    </div>
  </div>
		</div>
		
</section>	
		
</div>
	


</body>
</html>