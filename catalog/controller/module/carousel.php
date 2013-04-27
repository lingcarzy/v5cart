<?php  
class ControllerModuleCarousel extends Controller {
	protected function index($setting) {
		static $module = 0;
		
		M('design/banner');
		M('tool/image');
		
		$this->data['limit'] = $setting['limit'];
		$this->data['scroll'] = $setting['scroll'];
				
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);
		  
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}
		
		$this->data['module'] = $module++;
		$this->render('module/' . (!empty($setting['template']) ? $setting['template'] : 'carousel.tpl'));
	}
}
?>