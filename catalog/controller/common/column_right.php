<?php  
class ControllerCommonColumnRight extends Controller {
	protected function index() {

		$module_data = array();
		
		$extensions = C('cache_extension_module');			
		
		foreach ($extensions as $extension) {
			$modules = C($extension . '_module');
			
			if ($modules) {
				foreach ($modules as $module) {
					if ($module['layout_id'] == LAYOUT && $module['position'] == 'column_right' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension,
							'setting'    => $module,
							'sort_order' => $module['sort_order'],
							'id'         => isset($module['id']) ? $module['id'] : 0,
							'cache'      => isset($module['cache']) ? $module['cache'] : FALSE,
							'expire'	 => isset($module['expire']) ? $module['expire'] : 3600,
						);				
					}
				}
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$cache = $module['cache'] ? C('config_store_id') . $module['code'] . '.' . $module['id'] . 'R'. LAYOUT : FALSE;
			$module = $this->getChild('module/' . $module['code'], $module['setting'], $cache, $module['expire']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}
		
		$this->render('common/column_right.tpl');
	}
}
?>