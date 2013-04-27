<?php
class ControllerExtensionFeed extends Controller {
	public function index() {
		$this->language->load('extension/feed');

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
			'href'      => UA('extension/feed'),
			'separator' => ' :: '
   		);

		M('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('feed');

		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/feed/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('feed', $value);

				unset($extensions[$key]);
			}
		}

		$this->data['extensions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/feed/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->language->load('feed/' . $extension);

				$action = array();

				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => L('text_install'),
						'href' => UA('extension/feed/install', 'extension=' . $extension)
					);
				} else {
					$action[] = array(
						'text' => L('text_edit'),
						'href' => UA('feed/' . $extension)
					);

					$action[] = array(
						'text' => L('text_uninstall'),
						'href' => UA('extension/feed/uninstall', 'extension=' . $extension)
					);
				}

				$this->data['extensions'][] = array(
					'name'   => L('heading_title'),
					'status' => C($extension . '_status') ? L('text_enabled') : L('text_disabled'),
					'action' => $action
				);
			}
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('extension/feed.tpl');
	}

	public function install() {
		$this->language->load('extension/feed');

    	if (!$this->user->hasPermission('modify', 'extension/feed')) {
      		$this->session->set_flashdata('error', L('error_permission'));

			$this->redirect(UA('extension/feed'));
    	} else {
			M('setting/extension');
			$this->model_setting_extension->install('feed', $this->request->get['extension']);

			M('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'feed/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'feed/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/feed/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerFeed' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'install')) {
				$class->install();
			}

			$this->redirect(UA('extension/feed'));
		}
	}

	public function uninstall() {
		$this->language->load('extension/feed');

    	if (!$this->user->hasPermission('modify', 'extension/feed')) {
      		$this->session->set_flashdata('error', L('error_permission'));

			$this->redirect(UA('extension/feed'));
    	} else {
			M('setting/extension');
			M('setting/setting');

			$this->model_setting_extension->uninstall('feed', $this->request->get['extension']);

			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/feed/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerFeed' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}

			$this->redirect(UA('extension/feed'));
		}
	}
}
?>