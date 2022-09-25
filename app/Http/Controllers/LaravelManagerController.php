<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\LaravelMonologController;

class LaravelManagerController extends Controller {

	private function lastModifiedFile(array $files, string $path=""){
		$lastModified = -1;
		foreach($files as $file){
			$timestamp = File::lastModified($path.$file);
			if($timestamp >= $lastModified){
				$lastModified=$timestamp;
			}
		}
		return $lastModified;
	}
	
	private function stripArray($array){
		$nua_array=[];
		if($array){
			foreach($array as $k => $v){
				if(!empty($v)){
					$nua_array[]=$v;
				}
			}
		}
		
		return $nua_array;
	}
	
	private function getBasePath($project=""){
		return base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
	}
	
	private function goToRootPath(){
		chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
	}
	
	private function getProjectPath($project=false, $subPath=[], $file=false){
		$full_path = base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
		if($project){
			$full_path .= $project.DIRECTORY_SEPARATOR;
		}
		
		foreach($subPath as $path){
		    $full_path .= $path.DIRECTORY_SEPARATOR;	
		}
		
		if($file){
			$full_path .= $file;
		}
		
		return $full_path;
	}
	
	public function createProject($project=false){
	    $created=false;
		if($project){
			if(!is_dir($this->getBasePath().$project)){
			$this->goToRootPath();
			exec('composer create-project laravel/laravel '.$project.' > NUL');
			$created=true;
			}
		}
		return $created;
	}
	
	public function api(){
		$action = $_POST['action'];
		$project = $_POST['project'];
		if($action){
		switch($action){
			case 'shutdown':
				$process=$this->findProcess($project);
				if($process){
					$this->terminateProcess($process['id']);
				}
			break;
			case 'start':
				$this->enableProject($project);
			break;
			case 'stop':
				$this->disableProject($project);
			break;
			default:
				// do nothing;
			break;
		}
		}
	}
	
	public function getEnv($project=false){
		$data=[];
		if($project){
	    $project_path = base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
		chdir($project_path);
		$project_path=getcwd().DIRECTORY_SEPARATOR.$project;
		chdir(base_path());
		
		$content = file_get_contents($project_path.DIRECTORY_SEPARATOR.".env");
		$content=explode("\r\n", $content);
		foreach($content as $line){
			if(!empty($line)){
			$line_sep=explode("=", $line, 2);
			if(!array_key_exists(1, $line_sep)){
				$line_sep[] = "";
			}
			$data[$line_sep[0]]=$line_sep[1];
		}
		}
		}
		return $data;
	}
	
	public function updateEnv($project=null, $data=null){
		$updated = false;
		$envConfig = $this->getEnv($project);
		$nuaEnvConfig = [];
		$project_path = base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
		chdir($project_path);
		$project_path=getcwd().DIRECTORY_SEPARATOR.$project;
		chdir(base_path());
		$envFile=$project_path.DIRECTORY_SEPARATOR.".env";
		$count = 0;
		foreach($data as $key => $val){
			if(array_key_exists($val['name'], $envConfig)){
				$count+=1;
				$nuaEnvConfig[] = $val['name']."=".$val['value'];
			}
		}
		
		// Insert config data for VITE don't let admin do it.
		$nuaEnvConfig[] = 'VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"';
        $nuaEnvConfig[] = 'VITE_PUSHER_HOST="${PUSHER_HOST}"';
        $nuaEnvConfig[] = 'VITE_PUSHER_PORT="${PUSHER_PORT}"';
        $nuaEnvConfig[] = 'VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"';
        $nuaEnvConfig[] = 'VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"';
		
		if(file_put_contents($envFile, implode("\r\n", $nuaEnvConfig))){
			$updated=true;
		}
						  
		return $updated;
	}
	
	
	public function getLogs(string $project = null, bool $live = false, int $count = 0){
		$logs = false;
		if($project){
			$log_path = $this->getProjectPath($project, ["storage","logs"]);
			$monolog = new LaravelMonologController();
			$logs = $monolog->getLog("laravel.log", $log_path);
		}
	
		return $logs;
	}
	
	public function findProcess($project){
	    $process = false;
		$project_path = base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
		chdir($project_path);
		$project_path=getcwd().DIRECTORY_SEPARATOR.$project;
		chdir(base_path());
	    $os = php_uname("s");
		if(DIRECTORY_SEPARATOR=="\\"){
		// windows process
		exec('wmic process get processid,parentprocessid,executablepath,name,commandLine | find "cmd.exe"', $std_out, $ret_val);
		if($ret_val==0){
			foreach($std_out as $line){
		        $lines=explode(" ", $line);
				$lines=$this->stripArray($lines);
				//print_r($lines);
				if($lines[0]=="cmd"){
				    //echo "Found Proces<br/>";
					//print_r($lines);
					if(str_contains($lines[8], $project_path)){
						//echo json_encode("length: ".$lines[1236]);
						$host = parse_url($lines[7]);
						$process = array("directory"=>$lines[8],"id"=>$this->findWinProcessID($lines),"host"=>$host['host'],"port"=>$host['port']);
						//break;
					}
				}
				
			}
		}
		}
		return $process;
	}
	
	private function findWinProcessID($a){
		$nums=0;
		$pid=-1;
		$a=array_reverse($a);
		foreach($a as $k => $v){
			if(is_numeric($v)){
				$nums+=1;
				if($nums==2){
				    $pid=$v;	
					break;
				}
			}
		}
		return $pid;
	}
	
	public function terminateProcess($processID){
		$terminated=false;
		// check if windows
		if(DIRECTORY_SEPARATOR=="\\"){
		exec("taskkill /F /T /PID ".$processID, $std_out, $ret_val);
		if($ret_val==0 || $ret_val==128){
			$terminated=true;
		} else {
			$terminated=array("std_out"=>$std_out,"ret"=>$ret_val,"ID"=>$processID);
		}
		}
		return $terminated;
	}
	
	
	public function lockedLaravelPorts(){
		$projects = $this->refreshAppList();
		$ports = [];
		foreach($projects as $project){
		    $process_info = $this->findProcess($project);
			if($process_info){
			if(array_key_exists('port', $process_info)){
			if(is_numeric($process_info['port'])){
				$ports[] = $process_info['port'];
			}
			}
			}
		}
		if(count($ports)==0){
			$ports=false;
		}
		return $ports;
	}
	
	/*
	Commonly Used Restricted Port Range: 0 - 1023;
	Normal Registered Port Range: 1024 - 41951;
	Private / Non-Reserved Port Range: 41952 - 65535;
	Reference: https://www.sciencedirect.com/topics/computer-science/registered-port
	*/
	public function dynamicPortSelection(){
		$lockedPorts = $this->lockedLaravelPorts();
		$port = false;
		while(true){
		$randPort = rand(1023, 65535);
		if(!in_array($randPort, $lockedPorts)){
			$port = $randPort;
			break;
		}
		}
		return $port;
	}
	
	
	public function enableProject($project=false, $request=false){
		$enabled=false;
		if($project){
		$port=$this->dynamicPortSelection();
		$this->goToRootPath();
		if($port){
		$std_out=null;
		$ret_val=null;
		pclose(popen('start /B '.PHP_BINARY.' '.getcwd().DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR.'artisan serve --port '.$port.' > NUL','r'));
		$enabled=true;
		}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	public function disableProject($project){
		$disabled=false;
		$pid = $this->findProcess($project);
		if(array_key_exists("id",$pid)){
		$disabled=$this->terminateProcess($pid['id']);
		} else {
			$disabled=array("pid"=>null);
		}
		return $disabled;
	}
	
	public function isMaintenanceEnabled($project=false){
	    $enabled=false;
		if($project){
			chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
			chdir($project);
			if(file_exists(".".DIRECTORY_SEPARATOR."storage".DIRECTORY_SEPARATOR."framework".DIRECTORY_SEPARATOR."down")){
				$enabled=true;
			}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	public function enableMaintenanceMode($project=false, $request=false){
		$enabled=false;
		if($project){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec("php artisan down", $std_out, $ret_val);
		if($ret_val==0){
			$enabled=true;
		}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	public function disableMaintenanceMode($project=false, $request=false){
		$disabled=false;
		if($project){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec("php artisan up", $std_out, $ret_val);
		if($ret_val==0){
			$disabled=true;
		}
		}
		$this->goToRootPath();
		return $disabled;
	}
	
	
	/*
	Config Cache
	*/
	public function isConfigCacheEnabled($project){
	    $enabled=false;
		if($project){
		chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		chdir($project);
		if(file_exists(".".DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."config.php")){
			$enabled=true;
		}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	
	public function disableConfigCache($project=false, $request=false){
		$disabled=false;
		if($this->isConfigCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan config:clear', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$disabled=true;
			}
		}
		}
		$this->goToRootPath();
		return $disabled;
	}
	
	public function enableConfigCache($project=false, $request=false){
		$enabled=false;
		if(!$this->isConfigCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan config:cache', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$enabled=true;
			}
		}
		} else {
			$enabled=true;
		}
		$this->goToRootPath();
		return $enabled;
	}
	

	/*
	View Cache
	*/
	public function isViewCacheEnabled($project){
	    $enabled=false;
		if($project){
		chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		chdir($project);
		if(count(scandir(".".DIRECTORY_SEPARATOR."storage".DIRECTORY_SEPARATOR."framework".DIRECTORY_SEPARATOR."views")) > 3){
			$enabled=true;
		}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	
	public function disableViewCache($project=false, $request=false){
		$disabled=false;
		if($this->isViewCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan view:clear', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$disabled=true;
			}
		}
		}
		$this->goToRootPath();
		return $disabled;
	}
	
	public function enableViewCache($project=false, $request=false){
		$enabled=false;
		if(!$this->isViewCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan view:cache', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$enabled=true;
			}
		}
		} else {
			$enabled=true;
		}
		$this->goToRootPath();
		return $enabled;
	}

	public function isViewCacheValid($project=false){
		 $valid = array('valid'=>true);
		 if($project){
		    chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		    chdir($project);
			$views_cache_file_path=base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR."storage".DIRECTORY_SEPARATOR."framework".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR;
			$views_file_path=base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR;
			
			/* Get Array of View Cache Files */
            $views_cache_files=scandir($views_cache_file_path);
			 /* remove back directories array('.','..'); from $views_cache_files */
			$views_cache_files=array_splice($views_cache_files, 2);
			 /* find File::lastModified in $view array */
			$view_cache_mod_t=$this->lastModifiedFile($views_cache_files, $views_cache_file_path);
			
			/* Get Array of View Files */
			$views_files=scandir($views_file_path);
			/* remove back directories array('.','..'); from $views_files */
			$views_files=array_splice($views_files, 2);
			$valid['cache']=$views_cache_files;
			$valid['views']=$views_files;
			clearstatcache();
		    if(count($views_cache_files)>1){
			    foreach($views_files as $view){
					$file = $views_file_path.$view;
					clearstatcache();
					if(file_exists($file)){
						$view_mod_t = $this->quickDirtyFMT($file);
						if($view_mod_t >= $view_cache_mod_t){
							$valid['valid']=false;
						    break;
						}
					}
				}
		    }
		}
		
		$this->goToRootPath();
		return $valid;
	}
	
	/*
	Event Cache
	*/
	public function isEventCacheEnabled($project=false, $request=false){
	    $enabled=false;
		if($project){
		chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		chdir($project);
		if(file_exists(".".DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."events.php")){
			$enabled=true;
		}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	public function disableEventCache($project=false, $request=false){
		$disabled=false;
		if($this->isEventCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan event:clear', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$disabled=true;
			}
		}
		}
		$this->goToRootPath();
		return $disabled;
	}
	
	public function enableEventCache($project=false, $request=false){
		$enabled=false;
		if(!$this->isEventCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan event:cache', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$enabled=true;
			}
		}
		} else {
			$enabled=true;
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	
		public function isEventCacheValid($project=false){
		 $valid = array('valid'=>true);
		 if($project){
		    chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		    chdir($project);
			$event_cache_file_path=base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."events.php";
			$event_file_path=base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."providers".DIRECTORY_SEPARATOR;
			$events=["EventServiceProvider.php"];
			clearstatcache();
		    if(file_exists($event_cache_file_path)){
				$event_cache_mod_t=$this->quickDirtyFMT($route_cache_file_path);
			    foreach($events as $event){
					$file = $route_file_path.$route;
					clearstatcache();
					if(file_exists($file)){
						$event_mod_t = $this->quickDirtyFMT($file);
						if($event_mod_t >= $event_cache_mod_t){
							$valid['valid']=false;
						    break;
						}
					}
				}
		    }
		}
		$this->goToRootPath();
		return $valid;
	}
	
	
	/*
	Route Cache
	*/
	public function isRouteCacheEnabled($project=false, $request=false){
	    $enabled=false;
		if($project){
		chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		chdir($project);
		if(file_exists(".".DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."routes-v7.php")){
			$enabled=true;
		}
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	public function disableRouteCache($project=false, $request=false){
		$disabled=false;
		if($this->isRouteCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan route:clear', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$disabled=true;
			}
		}
		}
		$this->goToRootPath();
		return $disabled;
	}
	
	public function enableRouteCache($project=false, $request=false){
		$enabled=false;
		if(!$this->isRouteCacheEnabled($project)){
		chdir($project);
		$std_out=null;
		$ret_val=null;
		exec('php artisan route:cache', $std_out, $ret_val);
		if($ret_val==0){
			if(str_contains($std_out[1],'successfully')){
				$enabled=true;
			}
		}
		} else {
			$enabled=true;
		}
		$this->goToRootPath();
		return $enabled;
	}
	
	public function isRouteCacheValid($project=false){
		 $valid = array('valid'=>true);
		 if($project){
		    chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		    chdir($project);
			$route_cache_file_path=base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."routes-v7.php";
			$route_file_path=base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project.DIRECTORY_SEPARATOR."routes".DIRECTORY_SEPARATOR;
			$routes=["api.php","channels.php","console.php","web.php"];
			clearstatcache();
		    if(file_exists($route_cache_file_path)){
				$route_cache_mod_t=$this->quickDirtyFMT($route_cache_file_path);
			    foreach($routes as $route){
					$file = $route_file_path.$route;
					clearstatcache();
					if(file_exists($file)){
						$route_mod_t = $this->quickDirtyFMT($file);
						if($route_mod_t >= $route_cache_mod_t){
							$valid['valid']=false;
						    break;
						}
					}
				}
		    }
		}
		$this->goToRootPath();
		return $valid;
	}
	
	
	private function quickDirtyFMT($path=null){
		$time=-1;
		//echo "start /B ".PHP_BINARY." -r print_r(stat('".$path."')['mtime']);";

		$time=File::lastModified($path);
		//$time=shell_exec("php -r print_r(stat('".$path."')['mtime']);");
		return $time;
	}
	
	
	public function apiRequest($data){
		$call = false;
		$response = ["success"=>false,"data"=>null,"csrf-token"=>csrf_token()];
		chdir(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
	    if($data['project']){
		    switch($data['type']){
				case 'serve':
					if($data['action']==0){
						$call = $this->disableProject($data['project']);
					} else {
						$call = $this->enableProject($data['project']);
					}
				break;
				case 'maintenance':
					if($data['action']==0){
						$call = $this->disableMaintenanceMode($data['project']);
					} else {
						$call = $this->enableMaintenanceMode($data['project']);
					}
				break;
				case 'config':
					if($data['action']==0){
						$call = $this->disableConfigCache($data['project']);
					} else {
						$call = $this->enableConfigCache($data['project']);
					}
				break;
				case 'cache':
					if($data['action']==0){
						$call = $this->disableRouteCache($data['project']);
					} else {
						$call = $this->enableRouteCache($data['project']);
					}
				break;
				case 'view':
					if($data['action']==0){
						$call = $this->disableViewCache($data['project']);
					} else {
						$call = $this->enableViewCache($data['project']);
					}
				break;
				case 'event':
					if($data['action']==0){
						$call = $this->disableEventCache($data['project']);
					} else {
						$call = $this->enableEventCache($data['project']);
					}
				break;
				case 'create':
					$call = $this->createProject($data['project']);
				break;
				case 'process':
					$call = $this->findProcess($data['project']);
				case 'cache-check':
					switch($data['action']){
						case 'route':
							$call = $this->isRouteCacheValid($data['project']);
						break;
						case 'view':
							$call = $this->isViewCacheValid($data['project']);
						break;
						case 'event':
							$call = $this->isEventCacheValid($data['project']);
						break;
						case 'config':
							$call = $this->isConfigCacheValid($data['project']);
						break;
						default:
						break;
					};
				case 'environment':
					switch($data['action']){
						case 'update':
							$call = $this->updateEnv($data['project'], $data['data']);
						break;
						default:
						break;
					}
				break;
				case 'logs':
				    switch($data['action']){
						case 'get':
							$call = $this->getLogs($data['project']);
						break;
						case 'live':
							$call = $this->getLogs($data['project'], true, $data['count']);
						break;
						default:
						break;
					}
				break;
				default:
				break;
			}	
		}
		
		if($call){
			$response['success'] = true;
			$response['data'] = $call;
		}
		return $response;
	}
	
	public function refreshAppList(){
		$base = base_path();
		chdir($base.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		$root = getcwd();
		$root_files = scandir($root);
		$projects = [];
		foreach($root_files as $file){
			if(is_dir($file)){
				if(file_exists($file.DIRECTORY_SEPARATOR."composer.json") && file_exists($file.DIRECTORY_SEPARATOR."artisan")){
					$projects[] = $file;
				}
			}
		}
		return $projects;
	}
	
	
	// Function: parseRoutes($content, $sparsal=false);
	// Reason for Existance: parseRoutes is needed incase calling [cmd: php artisan route:cache] == failed
	// Description: parseRoutes parses all routes in the [app_root/routes] directory
	// Why did i code this instead of using regex?
	// Its probably faster than regex multi-sub array sequence order searches?
	// $sparsal == true (Returns contents inside $middle & $end;)
	// yo you screwed up the logic the exceptions are (strings, comments, other random BS)
	// leading to a de-inclination of the $meo variable count for successive 
	// closure & early termination resulting in partial parsed file content.
	
	public function regexSimple($content, $start="", $middle="", $end="", $sparsal=false){
	    $matches = [];
		$string="";
		$s=false;
		$m=false;
		$e=false;
		$sc=0;
		$mc=0;
		$ec=0;
		$meo=0;
		for($i = 0; $i <  strlen($content); $i++){
			$c = $content[$i];
			if(!$s){
			    if($c==$start[$sc]){
					if(!$sparsal){$string.=$c;}
					$sc+=1;
					if($sc==strlen($start)){
						$s=true;
					}
				}
			} else if(!$m){
				if($c==$middle[$mc]){
					if(!$sparsal){$string.=$c;}
					$mc+=1;
					$meo+=1;
					if($mc==strlen($middle)){
						$m=true;
					}
				}
			} else if(!$e){
				if($c==$end[$ec]){
					if($meo==1){
						if(!$sparsal){$string.=$c;}
						$s=false;
						$m=false;
						$e=false;
						$sc=0;
						$mc=0;
						$ec=0;
						$meo=0;
						$matches[]=$string;
						$string="";
					} else {
						$string.=$c;
						$meo-=1;
					}
				} else {
					if($c==$middle[$ec]){
						$meo+=1;
					}
					$string.=$c;
				}

			}
			
		}
		
		return $matches;
	}
	
	public function parseRoutePaths($routes){
		$route_paths=[];
		foreach($routes as $route){
			$splitRoute=explode(",", $route);
			$route_paths[] = $splitRoute[0];
		}
		return $route_paths;
	}
	
	public function getRoutes($project){
		chdir($project.DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."cache");
		$content=file_get_contents("routes-v7.php");
		$regex_content=str_replace("app('router')->setCompiledRoutes(", "", $this->regexSimple($content, "app('router')->setCompiledRoutes", "(", ")", false));
		$regex_content=implode("",$regex_content);
		$regex_content=substr($regex_content, 0, -1);
		chdir("..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		eval('$regex_content=array('.$regex_content.');');
		return $regex_content;
	}
	
	public function getRoutesFallback($project){
		//$cmd = 'php -r "use App\User; require __DIR__.'./vendor/autoload.php';  $app=require_once __DIR__.'./bootstrap/app.php'; $kernel=$app->make(Illuminate\Contracts\Http\Kernel::class); $response=$kernel->handle($request = Illuminate\Http\Request::capture()); include 'routes/web.php'; print_r(get_defined_functions());"';
		//echo base_path()."\\app\\Http\\Controllers\\cmd.php";
		//die();
		$cmd = "php ".base_path()."\\app\\Http\\Controllers\\cmd.php";
		
		exec($cmd, $std_out, $ret_val);
		if($ret_val==0){
			//$std_out=json_decode($std_out);
			print_r($std_out);
			die();
			$funcname = "";
			foreach($std_out->user as $key => $val){
				if($val == "route"){
					$funcname=$val;
					break;
				}
			}
			$refFunc = new \ReflectionFunction($funcname);

$params = array();
foreach($refFunc->getParameters() as $param){
    $params[] = $param->__toString();
}

print_r($params);
			
			die();
		}
		$output=false;
	    chdir($project.DIRECTORY_SEPARATOR."routes");
	    $content=file_get_contents(".".DIRECTORY_SEPARATOR."web.php");
		$routes=$this->regexSimple($content,"Route::", "(", ")", true);
		$routes=$this->parseRoutePaths($routes);
		$output=$routes;
		chdir("..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
		return $output;
	}
	
	public function getApp($project=false){
		$data = false;
		if($project){
		//$this->getRoutesFallback($project);
		$routes = [];
		$process_info = $this->findProcess($project);
		if(!$process_info){$process_info=array("directory"=>"","id"=>null,"host"=>"Localhost","port"=>"Unknown");}
		$validProjects = array("name"=>$project,
							   "cacheEnabled"=>$this->isRouteCacheEnabled($project),
							   "routes"=>[],
							   "enabled"=>$this->findProcess($project),
							   "maintenance"=>$this->isMaintenanceEnabled($project),
							   "config"=>$this->isConfigCacheEnabled($project),
							   "view"=>$this->isViewCacheEnabled($project),
							   "event"=>$this->isEventCacheEnabled($project),
							   "process"=>$process_info,
							   "environment"=>$this->getEnv($project));
			
		if($this->enableRouteCache(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project)){
		$route = $this->getRoutes(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project);
		if(array_key_exists(0, $route)){$route=$route[0];}
		if(array_key_exists("compiled", $route)){
		foreach($route['compiled'][1] as $key => $val){
			$validProjects['routes'][]=array("methods"=>$route['attributes'][$val[0][0]['_route']]['methods'],"uri"=>$route['attributes'][$val[0][0]['_route']]['uri']);
		};
		}
		} else {
		$routes[] = $this->getRoutesFallback($projects);
		}
		$data = $validProjects;
		}
		return $data;
	}
	
	public function appList(){
		$projects = $this->refreshAppList();
		$routes=[];
		$validProjects = [];
		foreach($projects as $project){
		$routes = [];
		$validProjects[] = array("name"=>$project,
								 "cacheEnabled"=>$this->isRouteCacheEnabled($project),
								 "routes"=>[],
								 "enabled"=>$this->findProcess($project),
								 "maintenance"=>$this->isMaintenanceEnabled($project),
								 "config"=>$this->isConfigCacheEnabled($project),
								 "view"=>$this->isViewCacheEnabled($project),
								 "event"=>$this->isEventCacheEnabled($project));
			
		if($this->enableRouteCache(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project)){
		$route = $this->getRoutes(base_path().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$project);
		if(array_key_exists(0, $route)){$route=$route[0];}
		if(array_key_exists("compiled", $route)){
		foreach($route['compiled'][1] as $key => $val){
			$validProjects[count($validProjects)-1]['routes'][]=array("methods"=>$route['attributes'][$val[0][0]['_route']]['methods'],"uri"=>$route['attributes'][$val[0][0]['_route']]['uri']);
		};
		}
		} else {
		$routes[] = $this->getRoutesFallback($projects);
		}
		}

	    return View::make("applist", array("page"=>"app","projects"=>$validProjects));	
	}
	
}