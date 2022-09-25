<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Laravel Project Manager</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
		<li class="nav-item">
        <a class="nav-link @if($page=='app') active @endif"  @if($page=='app') aria-current="page"  @endif href="{{ url('app/') }}">Home</a>
		 </li>
		 @if($page!='app' and $page!='create')
		 <li class="nav-item">
        <a class="nav-link @if($page=='manage') active @endif"  @if($page=='manage') aria-current="page"  @endif href="{{ url('manage/'.$project['name']) }}">Overview</a>
		  </li>
		<li class="nav-item">
        <a class="nav-link @if($page=='routes') active @endif"  @if($page=='routes') aria-current="page"  @endif href="{{ url('manage/'.$project['name'].'/routes/') }}">Routes</a>
		  </li>
		 <li class="nav-item">
		<a class="nav-link @if($page=='environment') active @endif"  @if($page=='environment') aria-current="page"  @endif href="{{ url('manage/'.$project['name'].'/environment/') }}">Environment</a>
		  </li>
		 
		 <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Database
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ url('manage/'.$project['name'].'/database/configure') }}">Configure</a></li>
            <li><a class="dropdown-item" href="{{ url('manage/'.$project['name'].'/database/migrate') }}">Migrate</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ url('manage/'.$project['name'].'/database/manage') }}">Manage</a></li>
			<li><a class="dropdown-item" href="{{ url('manage/'.$project['name'].'/database/create') }}">Create</a></li>
          </ul>
        </li>
		  
		 <li class="nav-item">
		<a class="nav-link @if($page=='logs') active @endif"  @if($page=='logs') aria-current="page"  @endif href="{{ url('manage/'.$project['name'].'/logs/') }}">Logs</a>
		  </li>
		 @endif
		 <li class="nav-item">
		<a class="nav-link @if($page=='create') active @endif"  @if($page=='create') aria-current="page"  @endif href="{{ url('app/create') }}">Create</a>
		  </li>
      </div>
    </div>
  </div>
</nav>