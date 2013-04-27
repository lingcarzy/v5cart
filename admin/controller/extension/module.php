<?php
class ControllerExtensionModule extends Controller {
	public function index() {
		$this->language->load('extension/module');

		$this->document->setTitle(L('heading_title'));
		$this->data['success'] = $this->session->flashdata('success');
		$this->data['error'] = $this->session->flashdata('error');
		
		M('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('module');

		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/module/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('module', $value);

				unset($extensions[$key]);
			}
		}

		$this->data['extensions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/module/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->language->load('module/' . $extension);

				$action = array();

				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => L('text_install'),
						'href' => UA('extension/module/install', 'extension=' . $extension)
					);
				} else {
					$action[] = array(
						'text' => L('text_edit'),
						'href' => UA('module/' . $extension)
					);

					$action[] = array(
						'text' => L('text_uninstall'),
						'href' => UA('extension/module/uninstall','extension=' . $extension)
					);
				}

				$this->data['extensions'][] = array(
					'name'   => L('module_name'),
					'action' => $action
				);
			}
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('extension/module.tpl');
	}

	public function install() {
		$this->language->load('extension/module');

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->set_flashdata('error', L('error_permission'));
			$this->redirect(UA('extension/module'));
		} else {
			M('setting/extension');
			$this->model_setting_extension->install('module', $this->request->get['extension']);

			M('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'install')) {
				$class->install();
			}

			$this->redirect(UA('extension/module'));
		}
	}

	public function uninstall() {
		$this->language->load('extension/module');

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->set_flashdata('error', L('error_permission'));

			$this->redirect(UA('extension/module'));
		} else {
			M('setting/extension');
			M('setting/setting');

			$this->model_setting_extension->uninstall('module', $this->request->get['extension']);

			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}

			$this->redirect(UA('extension/module'));
		}
	}
}
?>