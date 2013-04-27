<?php 
class ControllerDesignBanner extends Controller {
	
	public function index() {
		$this->language->load('design/banner');
		M('design/banner');		
		$this->getList();
	}

	public function insert() {
		$this->language->load('design/banner');
		M('design/banner');
		
		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_design_banner->addBanner($this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('design/banner'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('design/banner');		
		M('design/banner');
		
		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_design_banner->editBanner($this->request->get['banner_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('design/banner'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->language->load('design/banner');		
		M('design/banner');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $banner_id) {
				$this->model_design_banner->deleteBanner($banner_id);
			}
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('design/banner'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		
		$sort = $qf->get('sort', 'name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);
		
		$banner_total = $this->model_design_banner->getTotalBanners();		
		$results = $this->model_design_banner->getBanners($data);
		
		$this->data['banners'] = array();
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('design/banner/update', 'banner_id=' . $result['banner_id'])
			);

			$this->data['banners'][] = array(
				'banner_id' => $result['banner_id'],
				'name'      => $result['name'],	
				'status'    => ($result['status'] ? L('text_enabled') : L('text_disabled')),				
				'selected'  => isset($this->request->post['selected']) && in_array($result['banner_id'], $this->request->post['selected']),				
				'action'    => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');
		
		$url = ($order == 'ASC') ? 'DESC' : 'ASC';		
		
		$this->data['sort_name'] = UA('design/banner', 'sort=name&order=' . $url);
		$this->data['sort_status'] = UA('design/banner', 'sort=status&order=' . $url);
		
		$pagination = new Pagination();
		$pagination->total = $banner_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('design/banner', 'page={page}');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('design/banner_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$banner_id = $this->request->get('banner_id', 0);
		
		if ($banner_id) { 
			$this->data['action'] = UA('design/banner/update', 'banner_id=' . $banner_id);
		} else {
			$this->data['action'] = UA('design/banner/insert');
		}
		
		if ($banner_id && !$this->request->isPost()) {
			$banner_info = $this->model_design_banner->getBanner($banner_id);
		}

		$this->data['token'] = $this->session->data['token'];
			
		if (!empty($banner_info)) {
			$this->data['name'] = $banner_info['name'];
			$this->data['status'] = $banner_info['status'];
		} else {
			$this->data['name'] = P('name');
			$this->data['status'] = P('status', 1);
		}

		$this->data['languages'] = C('cache_language');
		
		M('tool/image');
	
		if (isset($this->request->post['banner_image'])) {
			$banner_images = $this->request->post['banner_image'];
		} elseif ($banner_id) {
			$banner_images = $this->model_design_banner->getBannerImages($banner_id);	
		} else {
			$banner_images = array();
		}
		
		$this->data['banner_images'] = array();
		
		foreach ($banner_images as $banner_image) {
			if ($banner_image['image'] && file_exists(DIR_IMAGE . $banner_image['image'])) {
				$image = $banner_image['image'];
			} else {
				$image = 'no_image.jpg';
			}			
			
			$this->data['banner_images'][] = array(
				'title' 				   => $banner_image['title'],
				'link'                     => $banner_image['link'],
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($image, 100, 100)
			);	
		} 
	
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);		

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('design/banner_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('name', '', 'required|range_length[3,64]', L('error_name'));
		
		if (isset($this->request->post['banner_image'])) {
			foreach ($this->request->post['banner_image'] as $banner_image_id => $banner_image) {
				foreach ($banner_image['title'] as $language_id => $title) {
					$this->form_validation->set_rules("banner_image[$banner_image_id][title][$language_id]", '', 'required|range_length[2,64]', L('error_title'));
				}
			}	
		}
		return $this->form_validation->run();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>