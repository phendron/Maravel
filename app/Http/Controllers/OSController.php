<?php

namespace App\Http\Controllers;



class OSController extends Controller {
	
	public function getInstalledApplications(){
		$cmd = 'powershell "$list = @(); $InstalledSoftware = (Get-ChildItem \'HKLM:\Software\Microsoft\Windows\CurrentVersion\Uninstall\'); foreach($obj in $InstalledSoftware){if($obj.getValue(\'DisplayName\') -And $obj.getValue(\'Publisher\') -And $obj.getValue(\'VersionMajor\')){write-host @($obj.getValue(\'DisplayName\')); write-host @($obj.getValue(\'Publisher\')); write-host @($obj.getValue(\'VersionMajor\'));};};"';
		exec($cmd, $std_out, $ret_val);

		$apps = [];
		for($i = 0; $i < count($std_out); $i+=3){
			$apps[] = array("name"=>$std_out[$i],"publisher"=>$std_out[$i+1],"version"=>$std_out[$i+2]);
		}
		return $apps;
	}
	
	
	public function isApplicationInstalled(bool $return_app = false, string $name = null){
		$apps = $this->getInstalledApplications();
		$callback = false;
		foreach($apps as $app){
			if(str_contains($app['name'], $name)){
				if($return_app){
				$callback=$app;
				$callback["installed"]=true;
				} else {
				$callback=true;
				}
				break;
			}
		}
		
		if($return_app && !$callback){
			$callback=array("name"=>$name,"publisher"=>null,"version"=>null,"installed"=>false);
		}
		return $callback;
	}
	
}

?>