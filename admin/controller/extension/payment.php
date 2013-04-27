<?php
class ControllerExtensionPayment extends Controller {
	public function index() {
		$this->language->load('extension/payment');
		 
		$this->document->setTitle(L('heading_title'));
		
		$this->data['heading_title'] = L('heading_title');
		
		$this->data['success'] = $this->session->flashdata('success');
		$this->data['error'] = $this->session->flashdata('error');
		
		$this->data['breadcrumbs'][] = array(
			'text'      => L('text_home'),
			'href'      => UA('common/home'),
			'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
			'text'      => L('heading_title'),
			'href'      => UA('extension/payment'),
			'separator' => ' :: '
   		);
		
		M('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('payment');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/payment/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('payment', $value);
				
				unset($extensions[$key]);
			}
		}
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/payment/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->language->load('payment/' . $extension);
	
				$action = array();
				
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => L('text_install'),
						'href' => UA('extension/payment/install', 'extension=' . $extension)
					);
				} else {
					$action[] = array(
						'text' => L('text_edit'),
						'href' => UA('payment/' . $extension)
					);
								
					$action[] = array(
						'text' => L('text_uninstall'),
						'href' => UA('extension/payment/uninstall', 'extension=' . $extension)
					);
				}
				
				$text_link = L('text_' . $extension);
				
				if ($text_link != 'text_' . $extension) {
					$link = L('text_' . $extension);
				} else {
					$link = '';
				}
				
				$this->data['extensions'][] = array(
					'name'       => L('heading_title'),
					'link'       => $link,
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
				
		$this->display('extension/payment.tpl');
	}
	
	public function install() {
		$this->language->load('extension/payment');
		
		if (!$this->user->hasPermission('modify', 'extension/payment')) {
			$this->session->set_flashdata('error', L('error_permission')); 
			
			$this->redirect(UA('extension/payment'));
		} else {
			M('setting/extension');
		
			$this->model_setting_extension->install('payment', $this->request->get['extension']);

			M('user/user_group');
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/payment/' . $this->request->get['extension'] . '.php');
			
			$class = 'ControllerPayment' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'install')) {
				$class->install();
			}
			
			$this->redirect(UA('extension/payment'));
		}
	}
	
	public function uninstall() {
		$this->language->load('extension/payment');
		
		if (!$this->user->hasPermission('modify', 'extension/payment')) {
			$this->session->set_flashdata('error', L('error_permission')); 
			
			$this->redirect(UA('extension/payment'));
		} else {		
			M('setting/extension');
			M('setting/setting');
				
			$this->model_setting_extension->uninstall('payment', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			require_once(DIR_APPLICATION . 'controller/payment/' . $this->request->get['extension'] . '.php');
			
			$class = 'ControllerPayment' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->redirect(UA('extension/payment'));	
		}			
	}
}
?>