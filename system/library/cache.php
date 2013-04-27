<?php
class Cache { 

	public function get($key, $dir = 'data', $serialize = TRUE) {
		$files = glob(DIR_CACHE . ($dir ? $dir.'/' : ''). 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$data = file_get_contents($files[0]);
			if ($serialize) $data = unserialize($data);			
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

      			if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);
					}
      			}
    		}
			
			return $data;			
		}
		return array();
	}

  	public function set($key, $value, $dir = 'data', $serialize = TRUE, $expire = 3600) {
    	$this->delete($key, $dir);
		
		$file = DIR_CACHE . ($dir ? $dir.'/' : ''). 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $expire);    	
		$handle = fopen($file, 'w');
		if ($serialize) $value = serialize($value);
    	fwrite($handle, $value);		
    	fclose($handle);
  	}
	
  	public function delete($key, $dir = 'data') {
		$files = glob(DIR_CACHE . ($dir ? $dir.'/' : ''). 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);
				}
    		}
		}
  	}
}
?>