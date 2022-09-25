<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\LaravelManagerController;
use App\Http\Controllers\OSController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/hash', function(){
	$t1 = microtime(true);
	$valid = false;
	if("1234567890098765432112345678900987654321"=="1234567890098765432112345678900987654321"){
		$valid = true;
	}
	$t2 = microtime(true) - $t1;
	$t3 = microtime(true);
	if("1234567890098765432112345678900987654321"=="0987654321123456789009876543211234567890"){
		$valid = true;
	}
	$t4 = microtime(true) - $t3;
	return view('hash', ['time1' => $t2, 'time2' => $t4]);
});

Route::get('/app', [LaravelManagerController::class, 'appList']);

Route::get('/app/create', function(){
	return view('manage-app-create', ["page"=>"create"]);
});

Route::get('/manage/{project}', function($project){
	$laravel = new LaravelManagerController();
	$project_data = $laravel->getApp($project);
	return view('manageapp', ["page"=>"manage","project"=>$project_data]);
});


Route::get('manage/{project}/environment', function($project){
	$laravel = new LaravelManagerController();
	$project_data = $laravel->getEnv($project);
	$project_data['name']=$project;
	return view('manage-app-environment', ["page"=>"environment","project"=>$project_data]);
});

Route::get('/manage/{project}/routes', function($project){
	$laravel = new LaravelManagerController();
	$project_data = $laravel->getApp($project);
	return view('manage-app-routes', ["page"=>"routes","project"=>$project_data]);
});

Route::get('manage/{project}/logs', function($project){
    $laravel = new LaravelManagerController();
	$project_data = $laravel->getApp($project);
	$logs_data = $laravel->getLogs($project);
	return view('manage-app-logs', ["page"=>"logs", "project"=>$project_data, "logs"=>$logs_data]);
});

Route::get('manage/{project}/database/configure', function($project){
	$os = new OSController();
	$laravel = new LaravelManagerController();
	$os_info = ["name"=>php_uname("s"),"arch"=>php_uname("m")];
	$environment_data = $laravel->getEnv($project);
	$apps = [];
	$apps['mysql'] = $os->isApplicationInstalled(true, 'MySQL Server');
	$apps['postgresql'] = $os->isApplicationInstalled(true, 'PostgreSQL');
	$apps['mariadb'] = $os->isApplicationInstalled(true, 'MariaDB');
	$apps['mssrv'] = $os->isApplicationInstalled(true, 'Microsoft SQL Server');
    return view('database.configure', ["application"=>$apps,"environment"=>$environment_data,"os_info"=>$os_info,"page"=>"database/configure", "project"=>["name"=>$project]]);	
});


Route::post('manage/{project}/api', function($project){
	$laravel = new LaravelManagerController();
	$post = array("project"=>$_POST['project'],"action"=>$_POST['action'],"type"=>$_POST['type']);
	if(array_key_exists('data', $_POST)){
		$post['data'] = $_POST['data'];
	}
	$request = $laravel->apiRequest($post);
	$data = array("success"=>$request);
	echo json_encode($data);
	die();
});

Route::post('manage/{project}/api/{action}', function($project, $action){
	$laravel = new LaravelManagerController();
	$post = array("project"=>$project, "action"=>$action);
	$request = $laravel->apiRequest($post);
	$data = array("success"=>$request);
	echo json_encode($data);
	die();
});

Route::post('app/create', function(){
	$laravel = new LaravelManagerController();
	$post = array("project"=>$_POST['project'],"type"=>$_POST['type']);
	$request = $laravel->apiRequest($post);
	$data = array("success"=>$request);
	echo json_encode($data);
	die();
});