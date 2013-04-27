<?php
class ControllerExtensionTotal extends Controller {
	public function index() {
		$this->language->load('extension/total');
		 
		$this->document->setTitle(L('heading_title')); 

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('extension/total'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = L('heading_title');
		$this->data['success'] = $this->session->flashdata('success');
		$this->data['error'] = $this->session->flashdata('error');

		M('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('total');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/total/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('total', $value);
				
				unset($extensions[$key]);
			}
		}
		
		$this->data['extensions'] = array();
				
		$files = glob(DIR_APPLICATION . 'controller/total/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->language->load('total/' . $extension);
	
				$action = array();
				
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => L('text_install'),
						'href' => UA('extension/total/install', 'extension=' . $extension)
					);
				} else {
					$action[] = array(
						'text' => L('text_edit'),
						'href' => UA('total/' . $extension)
					);
								
					$action[] = array(
						'text' => L('text_uninstall'),
						'href' => UA('extension/total/uninstall', 'extension=' . $extension)
					);
				}
										
				$this->data['extensions'][] = array(
					'name'       => L('heading_title'),
					'status'     => C($extension . '_status') ? L('text_enabled') : L('text_disabled'),
					'sort_order' => C($extension . '_sort_order'),
					'action'     => $action
				);
			}
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('extension/total.tpl');
	}
	
	public function install() {
		$this->language->load('extension/total');
			
		if (!$this->user->hasPermission('modify', 'extension/total')) {
			$this->session->set_flashdata('error', L('error_permission'));
			
			$this->redirect(UA('extension/total'));
		} else {				
			M('setting/extension');
		
			$this->model_setting_extension->install('total', $this->request->get['extension']);

			M('user/user_group');
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'total/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'total/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/total/' . $this->request->get['extension'] . '.php');
			
			$class = 'ControllerTotal' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'install')) {
				$class->install();
			}
			
			$this->redirect(UA('extension/total'));
		}
	}
	
	public function uninstall() {
		$this->language->load('extension/total');
		
		if (!$this->user->hasPermission('modify', 'extension/total')) {
			$this->session->set_flashdata('error', L('error_permission'));			
			$this->redirect(UA('extension/total'));
		} else {			
			M('setting/extension');
			M('setting/setting');
		
			$this->model_setting_extension->uninstall('total', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			require_once(DIR_APPLICATION . 'controller/total/' . $this->request->get['extension'] . '.php');
			
			$class = 'ControllerTotal' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->redirect(UA('extension/total'));
		}
	}	
}
?>