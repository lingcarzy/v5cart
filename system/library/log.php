<?php
class Log {
	private $filename;
	
	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	public function write($message, $filename = NULL) {
		if ($filename) {
			$file = DIR_LOGS . $filename;
		}
		else {
			$file = DIR_LOGS . $this->filename;
		}
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
		fwrite($handle, $_SERVER['REQUEST_URI'] . "\n\n");
		
		fclose($handle); 
	}
}
?>