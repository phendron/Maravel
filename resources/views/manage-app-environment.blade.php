<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="{{ URL::asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/manage-app-environment.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/manage-app-environment.js') }}"></script>
<title>Manage Project | Environment</title>
</head>

<body>
	
<section class="environment">

@include('menu.default')
	
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Environment</li>
  </ol>
</nav>
	
	
	
<h1>{{ $project['APP_NAME'] }} | Environment</h1>
	
<header class="window">
<button class="btn btn-primary active" action="application" aria-pressed="true">App</button>
<button class="btn btn-primary" action="log">Log</button>
<button class="btn btn-primary" action="database">Database</button>
<button class="btn btn-primary" action="misc">Misc</button>
<button class="btn btn-primary" action="redis">Redis</button>
<button class="btn btn-primary" action="mail">Mail</button>
<button class="btn btn-primary" action="aws">Aws</button>
<button class="btn btn-primary" action="pusher">Pusher</button>
</header>
	
<notification>
<msg class="success">Environment Configuration Saved Scuccessfully.</msg>
<msg class="error">There was an error saving the environment configuration. Please check the logs.</msg>
</notification>

<form class="environment">
<input type="hidden" name="project" value="{{ $project['name'] }}" />
<section class="application">
<h2>Application</h2>
	
<label for="APP_NAME">Name</label>
<input class="form-control" type="text" name="APP_NAME" value="{{ $project['APP_NAME'] }}" placeholder="" />
	
<label for="APP_ENV">Environment</label>
<select name="APP_ENV" class="form-select" aria-label="Default select example">
<option selected>Change App Environment:</option>
<option value="local" @if($project['APP_ENV']!="production") selected @endif >Local</option>
<option value="production" @if($project['APP_ENV']!="local") selected @endif >Production</option>
</select>
	
<label for="APP_KEY">Key</label>
<input class="form-control" type="text" name="APP_KEY" value="{{ $project['APP_KEY'] }}" placeholder="" />
	
<label for="APP_DEBUG">Debug</label>
<select name="APP_DEBUG" class="form-select" aria-label="Default select example">
<option selected>Turn Debug Mode (On / Off)</option>
<option value="true" @if($project['APP_DEBUG']!="false") selected @endif >On</option>
<option value="false" @if($project['APP_DEBUG']!="true") selected @endif >Off</option>
</select>
	
<label for="APP_URL">Url</label>
<input class="form-control" type="text" name="APP_URL" value="{{ $project['APP_URL'] }}" placeholder="" />
</section>

<section class="log">
<h2>Log</h2>
	
<label for="LOG_CHANNEL">Channel</label>
<input type="text" name="LOG_CHANNEL" value="{{ $project['LOG_CHANNEL'] }}" placeholder="" />
	
<label for="LOG_DEPRECATIONS_CHANNEL">Deprecations Channel</label>
<input type="text" name="LOG_DEPRECATIONS_CHANNEL" value="{{ $project['LOG_DEPRECATIONS_CHANNEL'] }}" placeholder="" />
	
<label for="LOG_LEVEL">Level</label>
<select name="LOG_LEVEL" class="form-select" aria-label="Default select example">
<option value="" @if($project['LOG_LEVEL']=="") selected @endif>Log Level - Off</option>
<option value="debug" @if($project['LOG_LEVEL']=="debug") selected @endif >Debug</option>
<option value="info" @if($project['LOG_LEVEL']=="info") selected @endif >Info</option>
<option value="error" @if($project['LOG_LEVEL']=="error") selected @endif >Error</option>
<option value="alert" @if($project['LOG_LEVEL']=="alert") selected @endif >Alert</option>
<option value="warning" @if($project['LOG_LEVEL']=="warning") selected @endif >Warning</option>
<option value="notice" @if($project['LOG_LEVEL']=="notice") selected @endif >Notice</option>
<option value="critical" @if($project['LOG_LEVEL']=="critical") selected @endif >Critical</option>
<option value="emergency" @if($project['LOG_LEVEL']=="emergency") selected @endif >Emergency</option>
	
</select>
	
</section>
	
	
<section class="database">
<h2>Database</h2>
	
<label for="DB_CONNECTION">Connection</label>
<input type="text" name="DB_CONNECTION" value="{{ $project['DB_CONNECTION'] }}" placeholder="" />
	
<label for="DB_HOST">Host</label>
<input type="text" name="DB_HOST" value="{{ $project['DB_HOST'] }}" placeholder="" />
	
<label for="DB_PORT">Port</label>
<input type="text" name="DB_PORT" value="{{ $project['DB_PORT'] }}" placeholder="" />
	
<label for="DB_DATABASE">Database</label>
<input type="text" name="DB_DATABASE" value="{{ $project['DB_DATABASE'] }}" placeholder="" />
	
<label for="DB_USERNAME">Username</label>
<input type="text" name="DB_USERNAME" value="{{ $project['DB_USERNAME'] }}" placeholder="" />
	
<label for="DB_PASSWORD">Password</label>
<input type="text" name="DB_PASSWORD" value="{{ $project['DB_PASSWORD'] }}" placeholder="" />
</section>
	
<section class="misc">
<h2>Misc</h2>
	
<label for="BROADCAST_DRIVER">Broadcast Driver</label>
<input type="text" name="BROADCAST_DRIVER" value="{{ $project['BROADCAST_DRIVER'] }}" placeholder="" />
	
<label for="CACHE_DRIVER">Cache Driver</label>
<input type="text" name="CACHE_DRIVER" value="{{ $project['CACHE_DRIVER'] }}" placeholder="" />
	
<label for="FILESYSTEM_DISK">Filesystem Disk</label>
<input type="text" name="FILESYSTEM_DISK" value="{{ $project['FILESYSTEM_DISK'] }}" placeholder="" />
	
<label for="QUEUE_CONNECTION">Queue Connection</label>
<input type="text" name="QUEUE_CONNECTION" value="{{ $project['QUEUE_CONNECTION'] }}" placeholder="" />
	
<label for="SESSION_DRIVER">Session Driver</label>
<input type="text" name="SESSION_DRIVER" value="{{ $project['SESSION_DRIVER'] }}" placeholder="" />
	
<label for="SESSION_LIFETIME">Session Lifetime</label>
<input type="text" name="SESSION_LIFETIME" value="{{ $project['SESSION_LIFETIME'] }}" placeholder="" />
	
<label for="MEMCACHED_HOST">Memcached Host</label>
<input type="text" name="MEMCACHED_HOST" value="{{ $project['MEMCACHED_HOST'] }}" placeholder="" />
</section>
	
<section class="redis">
<h2>REDIS</h2>
<label for="REDIS_HOST">Host</label>
<input type="text" name="REDIS_HOST" value="{{ $project['REDIS_HOST'] }}" placeholder="" />
	
<label for="REDIS_PASSWORD">Password</label>
<input type="text" name="REDIS_PASSWORD" value="{{ $project['REDIS_PASSWORD'] }}" placeholder="" />
	
<label for="REDIS_PORT">Port</label>
<input type="text" name="REDIS_PORT" value="{{ $project['REDIS_PORT'] }}" placeholder="" />
</section>
	
<section class="mail">
<h2>Mail</h2>
	
<label for="MAIL_MAILER">Mailer</label>
<input type="text" name="MAIL_MAILER" value="{{ $project['MAIL_MAILER'] }}" placeholder="" />
	
<label for="MAIL_HOST">Host</label>
<input type="text" name="MAIL_HOST" value="{{ $project['MAIL_HOST'] }}" placeholder="" />
	
<label for="MAIL_PORT">Port</label>
<input type="text" name="MAIL_PORT" value="{{ $project['MAIL_PORT'] }}" placeholder="" />
	
<label for="MAIL_USERNAME">Username</label>
<input type="text" name="MAIL_USERNAME" value="{{ $project['MAIL_USERNAME'] }}" placeholder="" />
	
<label for="MAIL_PASSWORD">Password</label>
<input type="text" name="MAIL_PASSWORD" value="{{ $project['MAIL_PASSWORD'] }}" placeholder="" />

<label for="MAIL_ENCRYPTION">Encryption</label>
<input type="text" name="MAIL_ENCRYPTION" value="{{ $project['MAIL_ENCRYPTION'] }}" placeholder="" />
	
<label for=="MAIL_FROM_ADDRESS">From Address</label>
<input type="text" name="MAIL_FROM_ADDRESS" value="{{ $project['MAIL_FROM_ADDRESS'] }}" placeholder="" />
	
<label for="MAIL_FROM_NAME">From Name</label>
<input type="text" name="MAIL_FROM_NAME" value="{{ $project['MAIL_FROM_NAME'] }}" placeholder="" />
</section>
	
<section class="aws">
<h2>AWS</h2>
	
<label for="AWS_ACCESS_KEY_ID">Access Key ID</label>
<input type="text" name="AWS_ACCESS_KEY_ID" value="{{ $project['AWS_ACCESS_KEY_ID'] }}" placeholder="" />
	
<label for="AWS_SECRET_ACCESS_KEY">Secret Access Key</label>
<input type="text" name="AWS_SECRET_ACCESS_KEY" value="{{ $project['AWS_SECRET_ACCESS_KEY'] }}" placeholder="" />
	
<label for="AWS_DEFAULT_REGION">Default Region</label>
<input type="text" name="AWS_DEFAULT_REGION" value="{{ $project['AWS_DEFAULT_REGION'] }}" placeholder="" />
	
<label for="AWS_BUCKET">Bucket</label>
<input type="text" name="AWS_BUCKET" value="" placeholder="{{ $project['AWS_BUCKET'] }}" />
	
<label for="AWS_USE_PATH_STYLE_ENDPOINT">Use Path Style Endpoint</label>
<select name="AWS_USE_PATH_STYLE_ENDPOINT" class="form-select" aria-label="Default select example">
<option selected>Turn Path Style Endpoint (On / Off)</option>
<option value="true" @if($project['AWS_USE_PATH_STYLE_ENDPOINT']!="false") selected @endif >On</option>
<option value="false" @if($project['AWS_USE_PATH_STYLE_ENDPOINT']!="true") selected @endif >Off</option>
</select>
</section>
	
<section class="pusher">
<h2>Pusher</h2>
	
<label for="PUSHER_APP_ID">App ID</label>
<input type="text" name="PUSHER_APP_ID" value="{{ $project['PUSHER_APP_ID'] }}" placeholder="" />
	
<label for="PUSHER_APP_KEY">App Key</label>
<input type="text" name="PUSHER_APP_KEY" value="{{ $project['PUSHER_APP_KEY'] }}" placeholder="" />
	
<label for="PUSHER_APP_SECRET">App Secret</label>
<input type="text" name="PUSHER_APP_SECRET" value="{{ $project['PUSHER_APP_SECRET'] }}" placeholder="" />
	
<label for="PUSHER_HOST">Host</label>
<input type="text" name="PUSHER_HOST" value="{{ $project['PUSHER_HOST'] }}" placeholder="" />
	
<label for="PUSHER_PORT">Port</label>
<input type="text" name="PUSHER_PORT" value="{{ $project['PUSHER_PORT'] }}" placeholder="" />
	
<label for="PUSHER_SCHEME">Scheme</label>
<input type="text" name="PUSHER_SCHEME" value="{{ $project['PUSHER_SCHEME'] }}" placeholder="" />
	
<label for="PUSHER_APP_CLUSTER">App Cluster</label>
<input type="text" name="PUSHER_APP_CLUSTER" value="{{ $project['PUSHER_APP_CLUSTER'] }}" placeholder="" />
</section>
	
<button class="btn btn-primary submit">Update</button>
</form>
	
</section>

</body>
</html>