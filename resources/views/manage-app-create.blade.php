<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="{{ URL::asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/manage-app-create.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/manage-app-create.js') }}"></script>
<title>Manage Project | Environment</title>
</head>

<body>
	
<section class="environment">

@include('menu.default')
	
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create App</li>
  </ol>
</nav>
	
	
<h1>Create Application</h1>

<form>
	
<input type="text" name="name" value="" placeholder="Application Name: e.g. My Awesome App" />

<button class="submit btn btn-primary">Create</button>
</form>

</body>
</html>