<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="{{ URL::asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/database/configure/default.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/database/configure/package.installer.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/database/configure/default.js') }}"></script>
<title>Manage Project | Database - Configure</title>
</head>

<body>
	
<section class="environment">

@include('menu.default')
	
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item">Database</li>
	<li class="breadcrumb-item active" aria-current="page">Configure</li>
  </ol>
</nav>
	
	
<h1>Database Configuration</h1>

<section>
<input type="hidden" name="system-os" value="{{ $os_info['name'] }}" />
<input type="hidden" name="system-arch" value="{{ $os_info['arch'] }}" />
	
<div class="row row-cols-1 row-cols-md-5 g-4 database-packages">
  <div class="col">
	  <input type="hidden" name="package" value="SQLite" />
    <div class="card h-100">
      <img style="object-fit:contain;height:250px;width:100%;" src="{{ URL::asset('images/sqlite-logo-trs.png') }}" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">SQLite Database Server</h5>
        <p class="card-text">Configure the Laravel App to use the Built-In SQLite Database Server.</p>
      </div>
      <div class="card-footer">
         <button class="btn btn-primary configure">Configure</button>
      </div>
    </div>
  </div>
	
  <div class="col">
	  <input type="hidden" name="package" value="PostgreSQL" />
    <div class="card h-100">
      <img style="object-fit:contain;height:250px;width:100%;" src="{{ URL::asset('images/PostgreSQL-logo.png') }}" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">PostgreSQL Database Server</h5>
        <p class="card-text">Configure the Laravel App to use the PostgreSQL Database Server.</p>
      </div>
      <div class="card-footer">
		   <a href="#"><button class="btn btn-primary install-package" package="postgresql" @if($application['postgresql']['installed']) disabled @endif>Install</button></a>
         <button class="btn btn-primary configure" @if(!$application['postgresql']['installed']) disabled @endif>Configure</button>
      </div>
    </div>
  </div>
	
  <div class="col">
	  <input type="hidden" name="package" value="MySQL" />
    <div class="card h-100">
      <img style="object-fit:contain;height:250px;width:100%;" src="{{ URL::asset('images/MySQL-logo.png') }}" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">MySQL Database Server</h5>
        <p class="card-text">Configure the Laravel App to use the MySQL Database Server.</p>
      </div>
      <div class="card-footer">
		   <a href="#"><button class="btn btn-primary install-package" package="mysql" @if($application['mysql']['installed']) disabled @endif>Install</button></a>
         <button class="btn btn-primary configure" @if(!$application['mysql']['installed']) disabled @endif>Configure</button>
      </div>
    </div>
  </div>
	
  <div class="col">
	  <input type="hidden" name="package" value="MariaDB" />
    <div class="card h-100">
      <img style="object-fit:contain;height:250px;width:100%;" src="{{ URL::asset('images/mariadb-logo.png') }}" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">MariaDB Database Server</h5>
        <p class="card-text">Configure the Laravel App to use the MariaDB Database Server.</p>
      </div>
      <div class="card-footer">
		   <a href="#"><button class="btn btn-primary install-package" package="mariadb" @if($application['mariadb']['installed']) disabled @endif>Install</button></a>
         <button class="btn btn-primary configure" @if(!$application['mariadb']['installed']) disabled @endif>Configure</button>
      </div>
    </div>
  </div>
	
  <div class="col">
	  <input type="hidden" name="package" value="MSsrv" />
    <div class="card h-100">
      <img style="object-fit:contain;height:250px;width:100%;" src="{{ URL::asset('images/ms-sql-srv-trs.png') }}" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">Microsoft SQL Database Server</h5>
        <p class="card-text">Configure the Laravel app to use a Microsoft SQL Database Server.</p>
      </div>
      <div class="card-footer">
		  <a href="#"><button class="btn btn-primary install-package" package="mssql" @if($application['mssrv']['installed']) disabled @endif>Install</button></a>
		 <button class="btn btn-primary configure" @if(!$application['mssrv']['installed']) disabled @endif>Configure</button>
      </div>
    </div>
  </div>
	
  <div class="col configure-database">
    <div class="card h-100">
      <div class="card-body">
        <form>
 <div class="form-group">
<label for="DB_CONNECTION">Database Connection</label>
<select class"form-control" name="DB_CONNECTION">
<option disabled>Choose Database Type</option>
<option value="sqlite" @if($environment['DB_CONNECTION']=='sqlitel') selected @endif>SQLite</option>
<option value="pgsql" @if($environment['DB_CONNECTION']=='pgsql') selected @endif>PostgreSQL</option>
<option value="mysql" @if($environment['DB_CONNECTION']=='mysql') selected @endif>MySQL</option>
<option value="mariadb" @if($environment['DB_CONNECTION']=='mariadb') selected @endif>MariaDB</option>
<option value="sqlsrv" @if($environment['DB_CONNECTION']=='sqlsrv') selected @endif>Microsoft SQL Server</option>
</select>
	</div>
 <div class="form-group">
<label for="DB_USERNAME">Database Username</label>
<input class"form-control" type="text" name="DB_USERNAME" value="{{ $environment['DB_USERNAME'] }}" placholder="Enter the Database Username" />
	</div>
 <div class="form-group">
<label for="DB_PASSWORD">Database Password</label>
<input class"form-control" type="password" name="DB_PASSWORD" value="{{ $environment['DB_PASSWORD'] }}" placholder="Enter the Database Username" />
	</div>
</form>	
      </div>
      <div class="card-footer">
		 <button class="btn btn-primary">Save</button>
		  <button class="btn btn-danger close">Cancel</button>
      </div>
    </div>
  </div>
	
</div>

	
<section class="info">
	
<p>
<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#alpine-instructions" aria-expanded="false" aria-controls="alpine-instruction"><badge><i class="alpine-ico"></i></badge>Alpine</button>
<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#debian-instructions" aria-expanded="false" aria-controls="debian-instruction"><badge><i class="debian-ico"></i></badge>Debian</button>
<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#redhat-instructions" aria-expanded="false" aria-controls="redhat-instruction"><badge><i class="redhat-ico"></i></badge>RedHat</button>
<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#suse-instructions" aria-expanded="false" aria-controls="suse-instruction"><badge><i class="suse-ico"></i></badge>SUSE</button>
<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#ubuntu-instructions" aria-expanded="false" aria-controls="ubuntu-instruction"><badge><i class="ubuntu-ico"></i></badge>Ubuntu</button>
</p>

<div class="collapse" id="alpine-instructions">
  <div class="card card-body">
@include('database.configure.install.alpine')
</div>
</div>
	
<div class="collapse" id="debian-instructions">
 <div class="card card-body">
@include('database.configure.install.debian')
</div>
</div>
	
<div class="collapse" id="redhat-instructions">
<div class="card card-body">
@include('database.configure.install.redhat')
</div>
</div>
<div class="collapse" id="suse-instructions">
<div class="card card-body">
@include('database.configure.install.suse')
</div>
</div>
	  
<div class="collapse" id="ubuntu-instructions">
<div class="card card-body">
@include('database.configure.install.ubuntu')
</div>
</div>
	  
</section>
	
</section>

</body>
</html>