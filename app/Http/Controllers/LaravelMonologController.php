<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class LaravelMonologController extends Controller {

	protected $logPattern = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/';
	protected $logPath = "".DIRECTORY_SEPARATOR."storage".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR;
	
	public function getLog(string $file, string $logPath=null){
		if($logPath==null){
			$logPath = $this->getLogPath();
		}
		$log_file_path = $logPath.$file;
		if(file_exists($log_file_path)){
			

			//$reader = new LogReader($log_file_path);
			$reader = $this->getMonologLog($log_file_path);
			$logs = [];
			foreach ($reader as $log) {
				if(array_key_exists("date", $log)){
					$log['date'] = $log['date']->format("Y-m-d H:i:s");
					$logs[] = $log;
				}
			}
			
			$logs=array_reverse($logs);
			return $logs;
		
		} else {
			return false;
		}
	}
	
	private function getLogPath(){
		return $this->logPath;
	}
	
	
private function getMonologLog(string $file = ""){

$pattern = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/';

$splFile = new \SplFileObject($file, 'r');

$i = 0;

$logs = [];
$line = "";
while (!$splFile->eof()) {
    $line.=$splFile->current();
    $line.=$splFile->next();
    $i++;

if(!is_string($line) || strlen($line) === 0) {
    $logs[] = array();
	continue;
}

preg_match($pattern, $line, $data);

if (!isset($data['date'])) {
    $logs[] = array();
	continue;
}

$logs[] = array(
'date' => \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']),
'logger' => $data['logger'],
'level' => $data['level'],
'message' => $data['message'],
'context' => json_decode($data['context'], true),
'extra' => json_decode($data['extra'], true)
);
	
$line="";
}
return $logs;
}

	
	
}

?>