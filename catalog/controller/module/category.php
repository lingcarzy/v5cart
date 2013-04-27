<?php  
class ControllerModuleCategory extends Controller {
	protected function index($setting) {
		global $CATEGORIES, $CATEGORY;
		
		$this->language->load('module/category');
		
		$this->data['heading_title'] = L('heading_title');
		
		$this->data['categories'] = $CATEGORIES;
		
		if ($CATEGORY) {
			$paths = explode(',', $CATEGORY['path']);
			if (count($paths) == 1) {
				$this->data['category_id'] = $paths[0];
				$this->data['child_id'] = 0;
			}
			elseif (count($paths) == 2) {
				$this->data['category_id'] = $paths[0];
				$this->data['child_id'] = $paths[1];
			}	
		}
		else {
			$this->data['category_id'] = 0;
			$this->data['child_id'] = 0;
		}
		$this->render('module/category.tpl');
  	}

}
?>